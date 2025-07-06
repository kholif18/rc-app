<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function index(Request $request)
    {
        $allFiles = UploadedFile::all();

        $totalFiles = $allFiles->count();
        $totalSize = $this->formatBytes($allFiles->sum('size'));
        $latestUpload = optional($allFiles->first())->uploaded_at?->format('Y-m-d H:i:s') ?? '-';

        return view('admin.files', compact('totalFiles', 'totalSize', 'latestUpload'));
    }

    public function getFilesJson()
    {
        $perPage = 10;
        $files = UploadedFile::orderByDesc('uploaded_at')->paginate($perPage);

        $fileDetails = $files->map(function ($file) {
            return [
                'name' => $file->original_name,
                'stored_name' => $file->stored_name,
                'size' => $this->formatBytes($file->size),
                'uploaded_at' => $file->uploaded_at->format('Y-m-d H:i:s'),
                'url' => asset("storage/client_uploads/{$file->stored_name}"),
                'extension' => strtoupper($file->extension),
                'icon' => $this->getFileIcon($file->extension),
            ];
        });

        return response()->json([
            'data' => $fileDetails,
            'current_page' => $files->currentPage(),
            'last_page' => $files->lastPage(),
        ]);
    }


    public function deleteFile(Request $request)
    {
        $request->validate(['filename' => 'required|string']);
        $filename = $request->filename;

        $file = UploadedFile::where('stored_name', $filename)->first();

        if (!$file) {
            return response()->json(['success' => false, 'message' => 'File tidak ditemukan di database'], 404);
        }

        $filePath = 'client_uploads/' . $filename;
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $file->delete();

        return response()->json(['success' => true, 'message' => 'File berhasil dihapus']);
    }

    public function deleteAll()
    {
        $files = UploadedFile::all();

        if ($files->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada file untuk dihapus'], 404);
        }

        foreach ($files as $file) {
            $filePath = 'client_uploads/' . $file->stored_name;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $file->delete();
        }

        return response()->json(['success' => true, 'message' => 'Semua file berhasil dihapus']);
    }

    public function downloadAll()
    {
        $zipFileName = 'client_uploads_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path("app/public/{$zipFileName}");

        // Ambil semua data dari database
        $files = UploadedFile::all();

        if ($files->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada file untuk di-zip.');
        }

        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $file) {
                $storedPath = storage_path("app/public/client_uploads/{$file->stored_name}");
                if (file_exists($storedPath)) {
                    // Gunakan nama asli sebagai nama dalam ZIP
                    $zip->addFile($storedPath, $file->original_name);
                }
            }
            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }


    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    // FileController.php
    public function forceDownload($filename)
    {
        $file = UploadedFile::where('stored_name', $filename)->firstOrFail();

        $path = storage_path("app/public/client_uploads/{$file->stored_name}");
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan di storage');
        }

        return response()->download($path, $file->original_name);
    }


    private function getFileIcon($extension)
    {
        $icons = [
            'pdf' => 'file-pdf',
            'jpg' => 'file-image',
            'jpeg' => 'file-image',
            'png' => 'file-image',
            'doc' => 'file-word',
            'docx' => 'file-word',
            'xls' => 'file-excel',
            'xlsx' => 'file-excel',
            'ppt' => 'file-powerpoint',
            'pptx' => 'file-powerpoint',
            'zip' => 'file-archive',
            'rar' => 'file-archive',
            'txt' => 'file-alt',
        ];

        return $icons[strtolower($extension)] ?? 'file';
    }
}
