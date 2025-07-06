<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientUploadController extends Controller
{
    /**
     * Menampilkan halaman upload file
     *
     * @return \Illuminate\View\View
     */
    public function showUploadForm()
    {
        return view('clients.index');
    }

    /**
     * Menangani proses upload file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|max:51200|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $storedName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();

            // Simpan file ke storage
            $file->storeAs('client_uploads', $storedName, 'public');

            // Simpan metadata ke database
            UploadedFile::create([
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'extension' => $extension,
                'size' => $size,
                'uploaded_at' => now(),
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'File berhasil diupload.']);
    }
}
