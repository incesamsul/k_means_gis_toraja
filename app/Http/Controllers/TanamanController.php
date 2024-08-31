<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class TanamanController extends Controller
{
    // Show all tanaman
    public function index()
    {
        $tanaman = Tanaman::all();
        return view('pages.tanaman.index', compact('tanaman'));
    }

    // Show the form to create a new alternative
    public function create()
    {
        $wilayah = Wilayah::all();
        return view('pages.tanaman.create', compact('wilayah'));
    }

    // Store a newly created alternative in the database
    public function store(Request $request)
    {

        Tanaman::create([
            'id_wilayah' => $request->wilayah_id,
            'luas_lahan' => $request->luas_lahan,
            'produksi' => $request->produksi,
            'produktivitas' => $request->produktivitas,
            'jenis_horikultura' => $request->jenis_horikultura,
            'persentase' => $request->persentase,
        ]);

        return redirect('/tanaman')
            ->with('success', 'Tanaman created successfully.');
    }



    // Show the form to edit a specific alternative
    public function edit(Tanaman $tanaman)
    {
        $wilayah = Wilayah::all();
        return view('pages.tanaman.edit', compact('wilayah', 'tanaman'));
    }

    // Update a specific alternative in the database
    public function update(Request $request, Tanaman $tanaman)
    {


        $tanaman->update([
            'id_wilayah' => $request->wilayah_id,
            'luas_lahan' => $request->luas_lahan,
            'produksi' => $request->produksi,
            'produktivitas' => $request->produktivitas,
            'jenis_horikultura' => $request->jenis_horikultura,
            'persentase' => $request->persentase,
        ]);

        return redirect('/tanaman')
            ->with('success', 'Tanaman updated successfully.');
    }

    // Delete a specific alternative from the database
    public function destroy(Tanaman $tanaman)
    {
        $tanaman->delete();

        return redirect('/tanaman')
            ->with('success', 'Tanaman deleted successfully.');
    }


    public function delete($tanaman_id){
        Tanaman::where('id', $tanaman_id)->delete();

        return redirect()->back()->with('warning', 'Tanaman berhasil hapus.');
    }

}
