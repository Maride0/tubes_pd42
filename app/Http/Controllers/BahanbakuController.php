<?php

namespace App\Http\Controllers;

use App\Models\bahanbaku;
use App\Http\Requests\StorebahanbakuRequest;
use App\Http\Requests\UpdatebahanbakuRequest;

class BahanbakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validated = $request->validate([
            'nama_bahan_baku' => 'required',
            'kategori_bahan_baku' => 'required',
        ]);
    
        $validated['kode_bahan_baku'] = Bahanbaku::getKodeBahanBaku(); // Ambil kode otomatis
    
        Bahanbaku::create($validated);
    
        return redirect()->route('bahanbaku.index')->with('success', 'Data berhasil ditambahkan');
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(bahanbaku $bahanbaku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(bahanbaku $bahanbaku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatebahanbakuRequest $request, bahanbaku $bahanbaku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(bahanbaku $bahanbaku)
    {
        //
    }
}
