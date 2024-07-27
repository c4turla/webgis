<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Aplikasi WebGIS Pariwisata</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Aplikasi WebGIS Pariwisata" name="description">
    <meta content="Kendariweb" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::to('assets/images/logogis.png') }}">
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::to('assets/images/logogis.png') }}">
    <link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-mouse-position@1.0.1/L.Control.MousePosition.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.2.1/dist/geosearch.css" />


    @stack('styles')
    @stack('scripts')
    @vite(['resources/css/map.css', 'resources/js/map.js'])
    @vite('resources/css/tailwind.css')
    <style>
        .text-center {
            text-align: center;
        }
        .main-container {
            display: flex;
            height: calc(100vh - 6.4rem);
            overflow: hidden; 
        }
        .nav {
            width: 300px; 
            border-right: 1px solid #ccc;
            overflow-y: auto;
            transition: width 0.3s ease; 
        }
        .main-content {
            flex: 1;
            overflow-y: auto;
        }
        #map {
            padding: 20px;
            z-index: 10;
            width: 100%;
            height: calc(100vh - 6.4rem); 
            position: relative;
        }   
        .map:-webkit-full-screen {
            height: 100%;
            margin: 0;
        }
        .map:fullscreen {
            height: 100%;
        }
        .map .ol-rotate {
            top: 3em;
        }
        @keyframes spinner {
            to {
            transform: rotate(360deg);
            }
        }
        .ol-zoom,
        .ol-rotate,
        .ol-overviewmap,
        .ol-full-screen {
            visibility: hidden;
        }

        .spinner:after {
            content: "";
            box-sizing: border-box;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 40px;
            height: 40px;
            margin-top: -20px;
            margin-left: -20px;
            border-radius: 50%;
            border: 5px solid rgba(180, 180, 180, 0.6);
            border-top-color: rgba(0, 0, 0, 0.6);
            animation: spinner 0.6s linear infinite;
        }
        .marker-label {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
        .marker-label:after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid rgba(255, 255, 255, 0.8);
            transform: translateX(-50%);
        }
        .coordinate-info {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: '#000';
            padding: 5px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            font-size: 12px;
            z-index: 1000;
        }
        .leaflet-control-container .leaflet-control-mouseposition {
            background: rgba(255, 255, 255, 0.8);
            padding : 10px;
            border-radius: 10px;
        } 
        .centered-bottom {
            position: fixed;
            bottom: 20px;
            left: 50%; /* Center horizontally */
            transform: translateX(-50%); /* Adjust for half of the button width */
            z-index: 50;
        }
        .leaflet-popup .leaflet-popup-content-wrapper {
            padding:10px;
            background: rgba(255, 255, 255, 0.8);
            
        }
        .leaflet-container a.leaflet-popup-close-button {
            color:#881337;
        }
        .leaflet-popup .leaflet-popup-content {
            margin : 10px 0 0 0;
        }
        .custom-mouse-position {
            position: absolute;
            bottom: 2em;
            left: 1em;
            padding: 0.5em;
            background: rgba(0, 60, 136, 0.7);
            color: white;
            font-family: 'Arial';
            font-size: 12px;
        }
    </style>
</head>
<body>
    @include('web.components.nav-header')
    <!-- Main container for sidebar and content -->
    <div class="main-container">
        <!-- Sidebar navigation -->
         <!-- @include('web.components.nav-left') -->
        <!-- Main content -->
        <div class="main-content">
            @yield('content')
            @vite('resources/js/tailwind.js')
            @yield('scripts')
        </div>
    </div>
</body>
</html>
<script src="{{ URL::to('assets/libs/lucide/umd/lucide.js') }}"></script>
<script src="{{ URL::to('assets/js/starcode.bundle.js') }}"></script>