<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Tempat;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tempat  = Tempat::all();
        $markers = $tempat->map(function($wisata) {
            return [
                'position' => [
                    'lat' => $wisata->latitude,
                    'lng' => $wisata->longitude,
                ],
                'label' => $wisata->nama_tempat,
               // 'imageUrl' => asset('storage/' . $wisata->gambar), // Asumsi gambar disimpan di storage
            ];
        });
        return view('admin.dashboard.home', ['tempat' => $markers->toJson()]);
    }
}
