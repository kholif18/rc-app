<?php

namespace App\Http\Controllers;

use App\Models\BahanCetak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BahanCetakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenis = $request->input('jenis');

        $bahanCetak = BahanCetak::query()
            ->search($search)
            ->jenis($jenis)
            ->orderBy('nama_bahan')
            ->paginate(10);

        $jenisBahan = BahanCetak::distinct('jenis_bahan')->pluck('jenis_bahan');

        return view('pengaturan.bahan-cetak.index', compact('bahanCetak', 'jenisBahan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan' => 'required|string|max:100',
            'jenis_bahan' => 'required|string|max:50',
            'gramatur' => 'nullable|string|max:20',
            'ukuran' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        BahanCetak::create($request->all());

        return redirect()->route('bahan-cetak.index')
            ->with('success', 'Bahan cetak berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(BahanCetak $bahanCetak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BahanCetak $bahanCetak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bahanCetak = BahanCetak::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_bahan' => 'required|string|max:100',
            'jenis_bahan' => 'required|string|max:50',
            'gramatur' => 'nullable|string|max:20',
            'ukuran' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        $bahanCetak->update($request->all());

        return redirect()->route('bahan-cetak.index')
            ->with('success', 'Bahan cetak berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bahanCetak = BahanCetak::findOrFail($id);
        $bahanCetak->delete();

        return redirect()->route('bahan-cetak.index')
            ->with('success', 'Bahan cetak berhasil dihapus');
    }

    /**
     * Get bahan cetak data for API
     */
    public function getBahanCetak(Request $request)
    {
        $bahanCetak = BahanCetak::query();

        if ($request->has('search')) {
            $bahanCetak->where('nama_bahan', 'like', '%'.$request->search.'%');
        }

        return response()->json($bahanCetak->get());
    }
}
