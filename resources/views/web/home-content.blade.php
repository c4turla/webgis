@extends('web.layouts.home')

@section('content')
<div x-data="map()" x-init="initComponent({{ json_encode($data) }})">
    <div x-ref="map" class="relative w-full h-[90vh] overflow-clip rounded-md border border-slate-300 shadow-lg">
        <div  class="absolute top-2 left-2 z-10 rounded-md bg-white bg-opacity-75 p-2 flex flex-col space-y-2">    
            <button x-on:click.prevent="zoomIn" title="Zoom In" class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                <i data-lucide="zoom-in" class="h-5" style="color: black;"></i>
            </button>
            <button x-on:click.prevent="zoomOut" title="Zoom Out" class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                <i data-lucide="zoom-out" class="h-5" style="color: black;"></i>
            </button>
        </div>   
        <div  class="absolute top-2 right-2 z-10 rounded-md bg-white bg-opacity-75 p-2 flex flex-col space-y-2">
            <button x-on:click.prevent="legendOpened = ! legendOpened" title="Open/Close legend" class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                <i data-lucide="settings" class="h-5" style="color: black;"></i>
            </button> 
            <button x-on:click.prevent="toggleFullScreen" title="Toggle Fullscreen"  class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                 <i data-lucide="expand" class="h-5" style="color: black;"></i>
            </button> 
            <button x-on:click.prevent="download" title="download"  class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                 <i data-lucide="download" class="h-5" style="color: black;"></i>
            </button>       
        </div>  
        <div  class="absolute bottom-2 left-2 z-10 rounded-md bg-white bg-opacity-75 p-2 flex flex-col space-y-2">
            <button  id="toggle-overview" class="overview-button flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                <i data-lucide="layers-2" class="h-5" style="color: black;"></i>
            </button>      
        </div>  
        <div x-cloak x-show="legendOpened" x-transition:enter="transition-opacity duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute right-0 top-0 left-2 bottom-2 z-10 max-w-sm rounded-md border border-slate-300 bg-white bg-opacity-50 shadow-sm">
            <div class="absolute inset-1 rounded-md bg-white bg-opacity-75 p-2">
                <div class="flex items-start justify-between">
                    <h3 class="tracking-widest font-semibold text-lg">Pengaturan Map</h3>
                    <button x-on:click.prevent="legendOpened = false"  class="text-2xl font-black text-slate-400 transition hover:text-[#3369A1] focus:text-[#3369A1] focus:outline-none">&times;</button>
                </div>
                <div class="flex flex-col gap-2 mt-6">
                    <label class="tracking-widest font-semibold">Lokasi</label>
                    <select id="kategori-select" class="text-slate-700 text-sm tracking-widest font-semibold py-2 px-4 text-lg border-none shadow-sm appearance-button outline-none bg-slate-100">
                        <option value="">Semua</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item['id_kategori'] }}">{{ $item['nama_kategori'] }}</option>
                        @endforeach
                    </select>
                </div>
                <ul>
                    <!-- Filter layers to show only those with the label 'Monuments' -->
                    <template x-for="(layer, index) in map.getAllLayers().reverse().filter(layer => layer.get('label') === 'Monuments')" :key="index">
                        <li class="flex items-center px-2 py-1 w-full">
                                <template x-if="layer.getVisible()" class="w-full">
                                    <div class="mt-2 px-2 text-sm text-slate-600 w-full">
                                        <template x-for="(feature, index) in layer.getSource().getFeatures()"
                                            :key="index">
                                            <div class="flex flex-row bg-black rounded-l-lg shadow-sm transition-transform duration-300 hover:-translate-y-2 w-full my-2">
                                                <div class="overflow-hidden transition-transform duration-300 transform items-center justify-center">   
                                                    <div class="relative w-20 h-20">
                                                        <img :src="feature.get('image')" alt='' class="absolute inset-0 w-full h-full object-cover rounded-l-lg transition-transform duration-300 transform">
                                                    </div>
                                                </div>
                                                <div class="text-gray-600 flex-col py-2 px-4 gap-1  bg-gradient-to-r from-white to-slate-200 w-full">
                                                    <h1 class="font-semibold text-sm text-black truncate">
                                                        <a href="#" :title="'Go to ' + feature.get('images')"
                                                            x-text="feature.get('name')" 
                                                            x-on:click.prevent="gotoFeature(feature)"
                                                            class="block hover:underline hover:text-slate-800 focus:outline-none focus:underline focus:text-slate-800 transition">
                                                        </a>
                                                    </h1>
                                                    <div class="flex items-center space-x-2">
                                                        <i class="fas fa-location-pin text-gray-400"></i>
                                                        <div x-text="truncate(feature.get('alamat'), 50)"
                                                            class="block text-gray-600 hover:underline hover:text-slate-800 focus:outline-none focus:underline focus:text-slate-800 transition">
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <i class="fas fa-location-pin text-gray-400"></i>
                                                        <div x-text="truncate(feature.get('alamat'), 50)"
                                                            class="block text-gray-600 hover:underline hover:text-slate-800 focus:outline-none focus:underline focus:text-slate-800 transition">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </li>
                    </template>
                </ul>
                <div class="flex flex-col gap-2 mt-6">
                    <label class="tracking-widest font-semibold">MAP</label>
                </div>
                <div x-data="mapData()">
                    <ul>
                        <template x-for="(layer, index) in  map.getAllLayers().reverse().filter(layer => layer.get('label') !== 'Monuments')" :key="index">
                            <li class="flex items-center px-2 py-1">
                                <div x-id="['legend-checkbox']">
                                    <label x-bind:for="$id('legend-checkbox')" class="flex items-center">
                                        <input type="checkbox" 
                                            :checked="isLayerVisible(layer)"
                                            x-bind:id="$id('legend-checkbox')"
                                            x-on:change="toggleLayer(layer)"
                                            class="rounded border-slate-300 text-[#3369A1] shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-slate-600" x-text="layer.get('label')"></span>
                                    </label>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
                <div x-cloak x-ref="popup" class="ol-popup ol-control transition">
                    <div class="p-2 m-0.5 bg-white rounded-md">
                        <div class="flex justify-between">
                            <h3 class="text-xs font-medium text-slate-400">Monument</h3>
                            <a href="#"
                                title="Close"
                                x-on:click.prevent="closePopup"
                                class="-mt-1 font-black text-slate-400 transition hover:text-slate-600 focus:text-slate-600 focus:outline-none">&times;</a>
                        </div>
                        <div x-ref="popupContent" class="mt-2 overflow-y-auto min-h-[200px]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function mapData() {
    return {
        selectedLayer: null,
        get sortedLayers() {
            // Return layers sorted by label with "OpenStreetMap" first
            return this.map.getAllLayers().reverse().sort((a, b) => {
                if (a.get('label') === 'OpenStreetMap') return -1;
                if (b.get('label') === 'OpenStreetMap') return 1;
                return 0;
            });
        },
        isLayerVisible(layer) {
            // Check if the layer is the currently selected one or if it is the Monuments layer
            return this.selectedLayer === layer || layer.get('label') === 'Monuments';
        },
        toggleLayer(layer) {
            // Log the label of the layer being toggled
            console.log(`Toggling layer: ${layer.get('label')}`);

            // If the layer is "Monuments", do not change its visibility
            if (layer.get('label') === 'Monuments') {
                return;
            }

            // If a layer is selected and it's not the current one, hide the selected layer
            if (this.selectedLayer && this.selectedLayer !== layer) {
                this.selectedLayer.setVisible(false);
            }

            // Toggle the visibility of the clicked layer
            if (this.selectedLayer === layer) {
                this.selectedLayer = null;
                layer.setVisible(false);
            } else {
                this.selectedLayer = layer;
                layer.setVisible(true);
            }
        },
        gotoFeature(feature) {
            this.$refs.mapComponent.map.getView().animate({
                center: feature.getGeometry().getCoordinates(),
                zoom: 15,
                duration: 500,
            });
        },
        initializeLayers() {
            // Ensure "Monuments" and "OpenStreetMap" layers are visible on initial load
            this.map.getAllLayers().forEach((layer) => {
                const label = layer.get('label');
                if (label === 'Monuments' || label === 'OpenStreetMap') {
                    layer.setVisible(true);
                    if (label !== 'Monuments') {
                        this.selectedLayer = layer;
                    }
                }
            });
        }
    };
}


function truncate(text, length) {
    return text.length > length ? text.substring(0, length) + '...' : text;
}

</script>
