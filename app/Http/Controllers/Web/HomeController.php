<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Tempat; 
use Illuminate\Http\JsonResponse;
use App\Models\Kategori; 

class HomeController extends Controller
{
   
    public function index(Request $request)
    {
        // Get list of places with their related photos
        $query = Tempat::with('foto');
        $getList = $query->get();

        $Kategori = Kategori::get()->toArray();
        // Format the data as required
        $formattedData = [
            "type" => "FeatureCollection",
            "features" => $getList->map(function ($tempat) {
                // Find the main photo where is_utama is not null
                $mainPhoto = $tempat->foto->firstWhere('is_utama', '!=', null);
                return [
                    "type" => "Feature",
                    "properties" => [
                        "id" => $tempat->id_tempat,
                        "name" => $tempat->nama_tempat,
                        "image" => $mainPhoto ? asset('storage/' . $mainPhoto->path) : null,
                        "kontak" => $tempat->kontak ? $tempat->kontak : " - ",
                        "waktu" => $tempat->jam_buka. " - " .$tempat->jam_tutup ,
                        "kategori" => $tempat->kategori_id ,
                        "deskripsi" => $tempat->deskripsi ,
                    ],
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [
                            (float)$tempat->longitude,
                            (float)$tempat->latitude
                        ]
                    ]
                ];
            })->toArray()
        ];
        return view('web.home-content', ['data' => $formattedData, 'kategori' =>  $Kategori ]);
    }

    public function getLokasiByKategori($kategoriId)
    {
        // Start building the query
        $query = Tempat::with('foto');
        
        // Apply the where clause only if kategoriId is not null or an empty string
        if ($kategoriId !== '0') {
            $query->where('kategori_id', $kategoriId);
        }
        
        $getList = $query->get();
    
        $formattedData = [
            "type" => "FeatureCollection",
            "features" => $getList->map(function ($tempat) {
                $mainPhoto = $tempat->foto->firstWhere('is_utama', '!=', null);
                return [
                    "type" => "Feature",
                    "properties" => [
                        "id" => $tempat->id_tempat,
                        "name" => $tempat->nama_tempat,
                        "image" => $mainPhoto ? asset('storage/' . $mainPhoto->path) : null,
                        "kontak" => $tempat->kontak ? $tempat->kontak : " - ",
                        "waktu" => $tempat->jam_buka. " - " .$tempat->jam_tutup ,
                        "kategori" => $tempat->kategori_id ,
                        "deskripsi" => $tempat->deskripsi ,
                    ],
                    "geometry" => [
                        "type" => "Point",
                        "coordinates" => [
                            (float)$tempat->longitude,
                            (float)$tempat->latitude
                        ]
                    ]
                ];
            })->toArray()
        ];
    
        return response()->json($formattedData);
    }
    public function detail($id): JsonResponse
    {
        $wisata = Tempat::with('foto')->findOrFail($id);
    
        $wisata->foto->transform(function ($foto) {
            $foto->path = asset('storage/' . $foto->path);
            return $foto;
        });

        return response()->json($wisata->toArray());
    }
    
}

