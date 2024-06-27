<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Brian2694\Toastr\Facades\Toastr;

class SettingController extends Controller
{
    //
    public function index()
    {
        $settings  = Setting::first();
        return view('admin.setting.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_aplikasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nama_instansi' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        // Ambil data setting pertama dari database
        $settings = Setting::first();

        // Update data setting
        $settings->update($request->all());
        Toastr::success('Data berhasil diperbarui :)','Success');

        // Redirect dengan pesan sukses
        return redirect()->back();
    }
}
