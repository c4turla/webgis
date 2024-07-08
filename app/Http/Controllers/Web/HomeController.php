<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $initialMarkers = [
            [
                'position' => [
                    'lat' => -3.91717,
                    'lng' => 122.08823
                ],
                'draggable' => true,
                'label' => 'Agra Wisata',
                'imageUrl' => 'https://shopee.co.id/inspirasi-shopee/wp-content/uploads/2022/06/mpGDXVHoSzKsqkWsopmjWg_thumb_470.webp' 
            ]
        ];

        return view('web.home-content', compact('initialMarkers'));
    }
}

