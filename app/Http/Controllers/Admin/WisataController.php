<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tempat;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Foto;

class WisataController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Tempat::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_tempat', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        }

        $wisatas = $query->paginate(10);
        return view('admin.wisata.index', compact('wisatas'));
    }

    public function create(){
        return view('admin.wisata.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate request
        $request->validate([
            'nama_tempat' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
        ]);

        // Create new Tempat
        $wisata = Tempat::create([
            'nama_tempat'  => $request->nama_tempat,
            'deskripsi'    => $request->deskripsi,
            'alamat'       => $request->alamat,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'longitude'    => $request->longitude,
            'kategori_id'  => 1,
            'jam_buka'     => $request->jam_buka,
            'jam_tutup'    => $request->jam_tutup,
            'harga_tiket'  => $request->harga_tiket,
            'fasilitas'    => $request->fasilitas,
            'kontak'       => $request->kontak,
            'status'       => $request->status
        ]);

            // Simpan gambar ke storage
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('images', 'public');

            // Simpan path gambar ke tabel foto
            Foto::create([
                'id_tempat' => $wisata->id_tempat,
                'nama_file' => $path,
            ]);
        }


        Toastr::success('Data berhasil ditambahkan :)','Success');

        // Redirect or return response
        return redirect()->route('wisata');
    }

    public function edit($id): View
    {
        $wisata = Tempat::findOrFail($id);
        return view('admin.wisata.edit', compact('wisata'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_tempat' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
        ]);

        $wisata = Tempat::findOrFail($id);

        $wisata->update([
            'nama_tempat'  => $request->nama_tempat,
            'deskripsi'    => $request->deskripsi,
            'alamat'       => $request->alamat,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'longitude'    => $request->longitude,
            'kategori_id'  => 1,
            'jam_buka'     => $request->jam_buka,
            'jam_tutup'    => $request->jam_tutup,
            'harga_tiket'  => $request->harga_tiket,
            'fasilitas'    => $request->fasilitas,
            'kontak'       => $request->kontak,
            'status'       => $request->status
        ]);


        Toastr::success('Data berhasil diubah :)','Success');

        // Redirect or return response
        return redirect()->route('wisata');

    }

}
