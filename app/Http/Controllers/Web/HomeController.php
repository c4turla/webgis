<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Tempat; 
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
                        "name" => $tempat->nama_tempat,
                        "image" => $mainPhoto ? asset('storage/' . $mainPhoto->path) : null,
                        "kontak" => $tempat->kontak ? $tempat->kontak : " - ",
                        "waktu" => $tempat->jam_buka. " - " .$tempat->jam_tutup ,
                        "kategori" => $tempat->kategori_id ,
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
                        "name" => $tempat->nama_tempat,
                        "image" => $mainPhoto ? asset('storage/' . $mainPhoto->path) : null,
                        "kontak" => $tempat->kontak ? $tempat->kontak : " - ",
                        "waktu" => $tempat->jam_buka. " - " .$tempat->jam_tutup ,
                        "kategori" => $tempat->kategori_id ,
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
    
}

