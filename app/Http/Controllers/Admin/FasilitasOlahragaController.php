<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tempat; 
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Foto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FasilitasOlahragaController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Tempat::query()->where('kategori_id', 2);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('kategori_id', 2)
                  ->where('nama_tempat', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        }

        $folahraga = $query->paginate(10);
        return view('admin.fasilitas-olahraga.index', compact('folahraga'));
    }

    public function create(){
        return view('admin.fasilitas-olahraga.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate request
        $request->validate([
            'nama_tempat' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
        ]);

        DB::beginTransaction();

        try {
            // Create new Tempat
            $olahraga = Tempat::create([
                'nama_tempat'  => $request->nama_tempat,
                'deskripsi'    => $request->deskripsi,
                'alamat'       => $request->alamat,
                'latitude'     => $request->latitude,
                'longitude'    => $request->longitude,
                'kategori_id'  => 2,
                'jam_buka'     => $request->jam_buka,
                'jam_tutup'    => $request->jam_tutup,
                'harga_tiket'  => $request->harga_tiket,
                'fasilitas'    => $request->fasilitas,
                'kontak'       => $request->kontak,
                'status'       => $request->status,
            ]);

            // Process each file
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $index => $file) {
                    // Generate a unique filename
                    $filename = time() . '_' . $file->getClientOriginalName();

                    // Store the file in the 'uploads' directory
                    $file->storeAs('uploads', $filename, 'public');

                    // Save the file path to the database
                    Foto::create([
                        'id_tempat' => $olahraga->id_tempat,
                        'nama_file' => $filename,
                        'deskripsi' => '',
                        'is_utama'  => null,
                        'urutan'    => $index + 1,
                        'path'      => 'uploads/' . $filename,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            Toastr::success('Data berhasil ditambahkan :)', 'Success');

        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            Toastr::error('Terjadi kesalahan, data tidak dapat ditambahkan :(', 'Error');
            return redirect()->back()->withInput();
        }

        // Redirect or return response
        return redirect()->route('fasilitas-olahraga');
    }

    public function edit($id): View
    {
        $folahraga = Tempat::with('foto')->findOrFail($id);
        return view('admin.fasilitas-olahraga.edit', compact('folahraga'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_tempat' => 'required',
            'deskripsi' => 'required',
            'alamat' => 'required',
        ]);
        DB::beginTransaction();

        $olahraga = Tempat::findOrFail($id);
        
        try {
            $olahraga->update([
                'nama_tempat'  => $request->nama_tempat,
                'deskripsi'    => $request->deskripsi,
                'alamat'       => $request->alamat,
                'latitude'     => $request->latitude,
                'longitude'    => $request->longitude,
                'longitude'    => $request->longitude,
                'kategori_id'  => 2,
                'jam_buka'     => $request->jam_buka,
                'jam_tutup'    => $request->jam_tutup,
                'harga_tiket'  => $request->harga_tiket,
                'fasilitas'    => $request->fasilitas,
                'kontak'       => $request->kontak,
                'status'       => $request->status
            ]);
            // Process each file
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $index => $file) {
                    // Generate a unique filename
                    $filename = time() . '_' . $file->getClientOriginalName();

                    // Store the file in the 'uploads' directory
                    $file->storeAs('uploads', $filename, 'public');

                    // Save the file path to the database
                    $fotoInput = Foto::create([
                        'id_tempat' => $olahraga->id_tempat,
                        'nama_file' => $filename,
                        'deskripsi' => '',
                        'is_utama'  => null,
                        'urutan'    => $index + 1,
                        'path'      => 'uploads/' . $filename,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            Toastr::success('Data berhasil diubah :)','Success');

        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            Toastr::error('Terjadi kesalahan, data tidak dapat diubah :(', 'Error');
            return redirect()->back()->withInput();
        }

        // Redirect or return response
        return redirect()->route('fasilitas-olahraga');

    }


    public function destroy(Request $request)
    {
        $id_tempat = $request->query('id');

        $item = Tempat::where('id_tempat', $id_tempat)->firstOrFail(); 
        $item->delete();
    
        if (!$item) {
            Toastr::success('Terjadi kesalahan. Data gagal dihapus :(','error');
            return redirect()->route('fasilitas-olahraga');
        }
        Toastr::success('Data berhasil dihapus :)','Success');
        return redirect()->route('fasilitas-olahraga');
    }

}
