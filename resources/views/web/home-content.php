@extends('web.layouts.home')

@section('content')
    <!-- Toggle button for sidebar -->
    <div class="centered-bottom flex flex-row gap-6 justify-center items-end">
        <div class="flex flex-col justify-center items-center gap-2 cursor-pointer group">
            <p class="bg-gray-300 p-2 rounded-lg transition-transform transform hover:scale-110 text-background text-xs hidden sm:group-hover:block ">Home</p>
            <button onclick="centerMap()" class="bg-background p-2 rounded-full transition-transform transform hover:scale-110">
                <i data-lucide="navigation" class="h-6 p-1" style="color: white;"></i>
            </button>
        </div>
        <div class="flex flex-col justify-center items-center gap-2 cursor-pointer group">
            <p class="bg-gray-300 p-2 rounded-lg transition-transform transform hover:scale-110 text-background text-xs hidden  sm:group-hover:block">Settings</p>
            <button onclick="toggleNav()" class="bg-background  p-2 rounded-full transition-transform transform hover:scale-110">
                <i data-lucide="settings" class="h-6 p-1" style="color: white;"></i>
            </button>
        </div>
        <div class="flex flex-col justify-center items-center gap-2 cursor-pointer group">
            <p class="bg-gray-300 p-2 rounded-lg transition-transform transform hover:scale-110 text-background text-xs hidden  sm:group-hover:block">Your location</p>
            <button onclick="addUserLocationMarker()" class="bg-background  p-2 rounded-full transition-transform transform hover:scale-110">
                <i data-lucide="map-pinned" class="h-6 p-1" style="color: white;"></i>
            </button>
        </div>
    </div>

    <div id="map" class="w-full h-screen"></div>
@endsection

@section('scripts')
<script src='https://unpkg.com/leaflet@1.8.0/dist/leaflet.js' crossorigin=''></script>
<script src='https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js'></script>
<script src='https://cdn.jsdelivr.net/npm/leaflet-label/dist/leaflet.label.js'></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-mouse-position@1.2.0/src/L.Control.MousePosition.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.6.2/proj4.js"></script>
<script src="https://unpkg.com/leaflet-geosearch@3.2.1/dist/bundle.min.js"></script>
<script>
    let map, markers = [];
    let initialCenter = {
        lat: -3.91717,
        lng: 122.0837
    };

    /* ----------------------------- Initialize Map ----------------------------- */
    function initMap() {
        map = L.map('map', {
            center: initialCenter,
            zoom: 10
        });

        // BASEMAPS
        var GoogleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: 'Google Streets'
        });

        var GoogleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: 'Google Hybrid'
        });

        var GoogleSatellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3'],
            attribution: 'Google Satellite'
        });

        const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        });

        const stadiamaps = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
        });

        const osm2 = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            attribution: '© Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
        });

        // BASEMAP LIST
        var baseLayers = {  
            'Google Hybrid': GoogleHybrid,
            'Google Satellite': GoogleSatellite,
            'Google Streets': GoogleStreets,
            'OpenStreetMap': osm,
            'StadiaMaps': stadiamaps,
            'OpenStreetMap 2': osm2
        };

        GoogleStreets.addTo(map);  // Add Google Streets as the default layer

        L.control.layers(baseLayers).addTo(map);

        // Add mouse position control
        L.control.mousePosition({ utm: true, position: 'bottomleft'}).addTo(map);

        initMarkers(); // Initialize markers after map is fully initialized
    }

    initMap();

    /* --------------------------- Initialize Markers --------------------------- */
    function initMarkers() {
        const initialMarkers = <?php echo json_encode($initialMarkers); ?>;

        for (let index = 0; index < initialMarkers.length; index++) {
            const data = initialMarkers[index];
            const marker = generateMarker(data, index);
            marker.addTo(map); // Add marker to the map
            markers.push(marker); // Push marker to markers array
        }
    }

    function generateMarker(data, index) {
        const marker = L.marker(data.position, {
            draggable: false  // Disable dragging for markers
        });

        // Add label to marker with custom CSS class
        marker.bindLabel(data.label, {
            noHide: true, // Keep label open
            className: 'marker-label' // Use custom CSS class for styling
        });

        // Add popup with label and image
        marker.bindPopup(`
            <div style="width: 250px;" class="p-1">
                <div class="text-gray-700 text-lg">${data.label}</div>
                <img src="${data.imageUrl}" alt="${data.label}" style="max-width: 100%; height: auto;" class="rounded-xl">
            </div>
        `, {
            minWidth: 250, 
            maxWidth: 600 
        });

        // Handle click event on marker
        marker.on('click', (event) => markerClicked(event, index));

        // Handle drag end event on marker
        marker.on('dragend', (event) => markerDragEnd(event, index));

        return marker;
    }

    /* ------------------------ Handle Marker Click Event ----------------------- */
    function markerClicked(event, index) {
        console.log(event.latlng.lat, event.latlng.lng);
    }

    /* ----------------------- Handle Marker DragEnd Event ---------------------- */
    function markerDragEnd(event, index) {
        console.log(event.target.getLatLng());
    }

    /* ------------------------------ Center Map ------------------------------ */
    function centerMap() {
        map.setView(initialCenter, 10); // Set view to initial center and zoom level
    }

    /* ----------------------- Add User Location Marker ------------------------ */
    function addUserLocationMarker() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const { latitude, longitude } = position.coords;
                const userLocation = L.marker([latitude, longitude], {
                    draggable: false
                }).addTo(map).bindPopup("You are here").openPopup();
                map.setView([latitude, longitude], 15); // Center map on user's location
                markers.push(userLocation); // Add user location marker to markers array
            }, (error) => {
                console.error("Error getting location: ", error);
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }





    
</script>

@endsection