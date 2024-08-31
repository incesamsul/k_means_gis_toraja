<?php

namespace App\Http\Controllers;

use App\Models\wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    // Show all wilayah
    public function index()
    {
        $wilayah = Wilayah::all();
        return view('pages.wilayah.index', compact('wilayah'));
    }

    // Show the form to create a new wilayah
    public function create()
    {
        return view('pages.wilayah.create');
    }

    // Store a newly created wilayah in the database
    public function store(Request $request)
    {
        $request->validate([
            'nama_wilayah' => 'required|string|max:255',
            'lokasi' => 'required',
            'koordinat' => 'required',
        ]);


        Wilayah::create([
            'nama_wilayah' => $request->nama_wilayah,
            'lokasi' => $request->lokasi,
            'koordinat' => trim(preg_replace('/\s+/', '', $request->koordinat)),
        ]);

        return redirect('/wilayah')
            ->with('success', 'wilayah created successfully.');
    }


    // Show the form to edit a specific wilayah
    public function edit(Wilayah $wilayah)
    {
        return view('pages.wilayah.edit', compact('wilayah'));
    }

    // Update a specific wilayah in the database
    public function update(Request $request, Wilayah $wilayah)
    {
        $request->validate([
        ]);


        $wilayah->update([
            'nama_wilayah' => $request->nama_wilayah,
            'lokasi' => $request->lokasi,
            'koordinat' => trim(preg_replace('/\s+/', '', $request->koordinat)),
        ]);

        return redirect('/wilayah')
            ->with('success', 'Wilayah updated successfully.');
    }

    // Delete a specific wilayah from the database
    public function destroy(Wilayah $wilayah)
    {
        $wilayah->delete();

        return redirect('/wilayah')
            ->with('success', 'Wilayah deleted successfully.');
    }


    public function delete($wilayah_id){
        Wilayah::where('id', $wilayah_id)->delete();

        return redirect('/wilayah')->with('warning', 'Wilayah berhasil hapus.');
    }

}
