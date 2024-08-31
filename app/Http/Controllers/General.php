<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request ;

class General extends Controller
{

    protected $userModel;

    public function __construct()
    {
    }

    public function dashboard()
    {
        $data['users'] = User::all()->count();
        $data['tanaman'] = Tanaman::all()->count();
        $data['wilayah'] = Wilayah::all()->count();
        return view('pages.dashboard.index', $data);
    }

    public function profile()
    {
        $data['user'] = User::where('id', auth()->user()->id)->first();
        return view('pages.profile.index', $data);
    }

    public function bantuan()
    {
        return view('pages.bantuan.index');
    }

    public function updateProfile(Request $request)
    {
        User::where('id', auth()->user()->id)->update([
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'aktivitas' => $request->aktivitas,
            'kalori' => $request->kalori
        ]);




        return redirect()->back()->with('success', 'Profile updated successfully');
    }



    public function ubahFotoProfile(Request $request)
    {

        $extensions = ['jpg', 'jpeg', 'png'];

        $result = array($request->foto->getClientOriginalExtension());

        if (in_array($result[0], $extensions)) {
            $file = $request->foto;
        } else {
            return redirect()->back()->with('error', 'format file tidak di dukung');
        }

        // $fileName = auth()->user()->email . "." . $request->foto->extension();
        $fileName = uniqid() . "." . $request->foto->extension();
        $request->foto->move(public_path('data/foto_profile/' . $fileName . '/'), $fileName);

        // Storage::disk('uploads')->put($fileName, file_get_contents($request->foto->getRealPath()));

        User::where('id', auth()->user()->id)
            ->update(['foto' => $fileName]);
        return redirect()->back();
    }
}
