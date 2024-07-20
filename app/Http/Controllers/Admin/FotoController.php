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

class FotoController extends Controller
{
    public function destroy($id)
    {
        $id_foto =  $id;
        $item = Foto::where('id_foto', $id_foto)->firstOrFail();
        
        // Check if the is_utama column is null
        if (!is_null($item->is_utama)) {
            Toastr::error('Foto utama tidak dapat dihapus :(','Error');
            return redirect()->back()->withInput();
        }

        $filePath = $item->path;
    
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    
        // Delete the record from the database
        $item->delete();
    
        Toastr::success('Data berhasil dihapus :)','Success');
        return redirect()->back()->withInput();
    }

    
    public function updateIsUtama(Request $request, $id)
    {
        $id_foto = $id;
        $foto = Foto::where('id_foto', $id_foto)->firstOrFail();

        if ($foto) {
            // Retrieve id_tempat of the current photo
            $id_tempat = $foto->id_tempat;

            // Begin a transaction
            DB::beginTransaction();

            try {
                // Set is_utama to null for all photos with the same id_tempat
                Foto::where('id_tempat', $id_tempat)->update(['is_utama' => null]);

                // Set is_utama to 1 for the current photo
                $foto->is_utama = 1;
                $foto->save();

                // Commit the transaction
                DB::commit();

                Toastr::success('Data berhasil diupdate :)', 'Success');
            } catch (\Exception $e) {
                // Rollback the transaction
                DB::rollBack();

                Toastr::error('Data gagal diupdate :(', 'Error');
            }

            return redirect()->back()->withInput();
        } else {
            Toastr::error('Data gagal diupdate :(', 'Error');
            return redirect()->back()->withInput();
        }
    }
}