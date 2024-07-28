@extends('web.layouts.home')

@section('content')
<div x-data="map()" x-init="initComponent({{ json_encode($data) }})">
    <div x-ref="map" class="relative w-full h-[90vh] overflow-clip rounded-md border border-slate-300 shadow-lg">
        <div  class="absolute top-2 right-2 z-10 rounded-md bg-white bg-opacity-75 p-2 flex flex-col space-y-2">
            <button x-on:click.prevent="legendOpened = ! legendOpened" title="Open/Close legend" class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                <i data-lucide="settings" class="h-5" style="color: black;"></i>
            </button> 
            <button x-on:click.prevent="zoomIn" title="Zoom In" class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                <i data-lucide="zoom-in" class="h-5" style="color: black;"></i>
            </button>
            <button x-on:click.prevent="zoomOut" title="Zoom Out" class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                <i data-lucide="zoom-out" class="h-5" style="color: black;"></i>
            </button>
            <button x-on:click.prevent="toggleFullScreen" title="Toggle Fullscreen"  class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                 <i data-lucide="expand" class="h-5" style="color: black;"></i>
            </button> 
            <button x-on:click.prevent="download" title="download"  class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                 <i data-lucide="download" class="h-5" style="color: black;"></i>
            </button>   
            <button x-on:click.prevent="findLocation" title="findLocation"  class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                 <i data-lucide="location" class="h-5" style="color: black;"></i>
            </button>  
            <div x-data="{ showModal: false }">
                <!-- Button to open the modal -->
                <button 
                    id="btnModalMap" 
                    @click="showModal = !showModal" 
                    class="flex items-center justify-center p-2 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                    <i data-lucide="layers-2" class="h-5" style="color: black;"></i>
                </button>   
                <!-- Modal map -->
                <div 
                    id="modalMap" 
                    x-show="showModal" 
                    class="absolute  w-20 top-40 right-16 z-10 rounded-md bg-white bg-opacity-75 p-2 flex flex-col space-y-2" 
                    x-cloak>
                    <button @click="switchToOSM(); showModal = false" title="openStreets Basemap" class="flex items-center justify-center p-1 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                        <img src="/assets/images/map/openStreets.png" alt="openStreets" class="basemap-icon h-10">
                    </button>  
                    <button @click="switchToArcGIS(); showModal = false" title="argis Basemap" class="flex items-center justify-center p-1 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                        <img src="/assets/images/map/argis.png" alt="argis" class="basemap-icon h-10">
                    </button>  
                    <button @click="switchToXYZ(); showModal = false" title="XYZ Basemap" class="flex items-center justify-center p-1 bg-white border border-slate-300 rounded-md shadow-sm hover:bg-slate-100 focus:bg-slate-100">
                        <img src="/assets/images/map/thunderforest.png" alt="XYZ" class="basemap-icon h-10">
                    </button> 
                </div>
            </div>
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
                 <!-- Tabs Navigasi -->
                <div class="flex border-b border-gray-200">
                    <button id="tab-lokasi" class="tracking-widest font-semibold py-2 px-4 text-sm font-semibold text-gray-700 border-b-2 border-transparent hover:border-gray-300 focus:outline-none">Lokasi</button>
                    <button id="tab-events" class="tracking-widest font-semibold py-2 px-4 text-sm font-semibold text-gray-700 border-b-2 border-transparent hover:border-gray-300 focus:outline-none">Events</button>
                </div>

                <!-- Tab Content -->
                <div id="tab-content-lokasi" class="mt-4 hidden">
                    <!-- Lokasi Content -->
                    <div class="flex flex-col gap-2 mt-6">
                        <select id="kategori-select" class="text-slate-700 text-sm tracking-widest font-semibold py-2 px-4 text-lg border-none shadow-sm appearance-button outline-none bg-slate-100">
                            <option value="0">Semua</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item['id_kategori'] }}">{{ $item['nama_kategori'] }}</option>
                            @endforeach
                        </select>
                        <div x-data="{
                                currentPage: 1,
                                itemsPerPage: 7,
                                get totalPages() {
                                    return Math.ceil(this.layer.getSource().getFeatures().length / this.itemsPerPage);
                                },
                                get paginatedFeatures() {
                                    const start = (this.currentPage - 1) * this.itemsPerPage;
                                    const end = this.currentPage * this.itemsPerPage;
                                    return this.layer.getSource().getFeatures().slice(start, end);
                                },
                                nextPage() {
                                    if (this.currentPage < this.totalPages) {
                                        this.currentPage++;
                                    }
                                },
                                prevPage() {
                                    if (this.currentPage > 1) {
                                        this.currentPage--;
                                    }
                                }
                            }"
                        >
                        <ul>
                            <template x-for="(layer, index) in map.getAllLayers().reverse().filter(layer => layer.get('label') === 'Monuments')" :key="index">
                                <li class="flex items-center px-2 py-1 w-full">
                                    <template x-if="layer.getVisible()" class="w-full">
                                        <div class="mt-2 px-2 text-sm text-slate-600 w-full">
                                            <template x-for="(feature, index) in paginatedFeatures" :key="index">
                                                <div x-on:click.prevent="gotoFeature(feature)" class="cursor-pointer relative flex flex-row bg-white bg-opacity-75 rounded-l-lg shadow-lg transition-transform duration-300 hover:-translate-y-2 w-full my-2">
                                                    <div class="overflow-hidden transition-transform duration-300 transform items-center justify-center">
                                                        <div class="relative w-20 h-20">
                                                            <img :src="feature.get('image')" alt='' class="absolute inset-0 w-full h-full object-cover rounded-l-lg transition-transform duration-300 transform">
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 flex-col py-2 px-4 gap-1 bg-white bg-opacity-75 w-full">
                                                        <h1 class="font-semibold text-sm text-black flex items-center space-x-2">
                                                            <i data-lucide="map-pin" class="h-3" style="color: black;"></i>
                                                            <div x-text="feature.get('name')" class="block text-gray-700 focus:outline-none focus:underline focus:text-slate-800 transition"></div>
                                                        </h1>
                                                        <div class="flex items-center space-x-2">
                                                            <i data-lucide="contact" class="h-3" style="color: black;"></i>
                                                            <div x-text="feature.get('kontak')" class="block text-gray-500 hover:text-slate-800 focus:outline-none focus:underline focus:text-slate-800 transition"></div>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <i data-lucide="clock-9" class="h-3" style="color: black;"></i>
                                                            <div x-text="feature.get('waktu')" class="block text-gray-500 hover:text-slate-800 focus:outline-none focus:underline focus:text-slate-800 transition"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <!-- Pagination Controls -->
                                            <div class="w-full flex justify-center items-center mt-4 absolute bottom-2 -ml-5">
                                                <div class="flex justify-between items-center w-full max-w-xs">
                                                    <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 rounded disabled:opacity-50">
                                                        Previous
                                                    </button>
                                                    <span class="text-sm text-gray-600">Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
                                                    <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 rounded disabled:opacity-50">
                                                        Next
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </li>
                            </template>
                        </ul>
                    </div>

                    </div>
                </div>
                <div id="tab-content-events" class="mt-4 hidden">
                    <!-- Lokasi Content -->
                    <div class="flex flex-col gap-2 mt-6">
                        
                    </div>
                </div>  
                <div x-cloak x-ref="popup" class="ol-popup ol-control transition  bg-slate-100 p-1">
                    <div class="p-2 m-0.5 bg-slate-100 rounded-md">
                        <div class="flex justify-end">
                            <a href="#"
                                title="Close"
                                x-on:click.prevent="closePopup"
                                class="-mt-1 font-black text-slate-400 transition hover:text-slate-600 focus:text-slate-600 focus:outline-none">&times;</a>
                        </div>
                        <div x-ref="popupContent" class="overflow-y-auto min-h-[200px]"></div>
                    </div>
                </div>
            </div>
        </div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('button[id^="tab-"]');
        const contents = document.querySelectorAll('div[id^="tab-content-"]');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('border-gray-300', 'text-blue-500'));
                contents.forEach(c => c.classList.add('hidden'));

                this.classList.add('border-gray-300', 'text-blue-500');
                document.getElementById('tab-content-' + this.id.split('-')[1]).classList.remove('hidden');
            });
        });

        // Set default tab
        document.getElementById('tab-lokasi').click();
    });
    
</script>

<script>
  document.addEventListener("alpine:init", () => {
    Alpine.data("imageSlider", (images) => ({
      currentIndex: 1,
      images: images,
      previous() {
        if (this.currentIndex > 1) {
          this.currentIndex = this.currentIndex - 1;
        }
      },
      forward() {
        if (this.currentIndex < this.images.length) {
          this.currentIndex = this.currentIndex + 1;
        }
      },
    }));
  });
</script>