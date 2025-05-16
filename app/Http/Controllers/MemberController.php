<?php

namespace App\Http\Controllers;

use App\Models\member;
use App\Http\Requests\StorememberRequest;
use App\Http\Requests\UpdatememberRequest;

// tambahan untuk menangani form request
use Illuminate\Foundation\Http\FormRequest;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //query data
        Member::where('id_member', $id)->first();

        return view('member.view',
                    [
                        'member' => $member
                    ]
                  );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member.create',
                    [
                        'id_Member' => Member::getidmember()
                    ]
                  );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorememberRequest $request)
    {
        $validated = $request->validate([
            'id_member' => 'required',
            'nama' => 'required|unique:member|min:5|max:255',
            'no_telp' => 'required',
        ]);
    
        Member::create([
            'id_member' => $validated['id_member'],
            'nama' => $validated['nama'],
            'no_telp' => $validated['no_telp'],
            'alamat' => $request->alamat, // tambahkan kalau ada field ini
            'user_id' => auth()->id(),    // pastikan user sudah login
        ]);
    
        return redirect()->route('member.index')->with('success', 'Data member berhasil ditambahkan.');
    }    

    /**
     * Display the specified resource.
     */
    public function show(member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(member $member)
    {
        return view('member.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatememberRequest $request, member $member)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru diupdate ke db
        $validated = $request->validate([
            'id_member' => 'required',
            'nama' => 'required|min:5|max:255',
            'no_telp' => 'required',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //hapus dari database
        $member = member::findOrFail($id);
        $member->delete();

        return redirect()->route('member.index')->with('success','Data Berhasil di Hapus');
    }
}