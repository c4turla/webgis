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
                    <h3 class="text-lg font-medium text-slate-700">Legend</h3>
                    <button x-on:click.prevent="legendOpened = false"
                        class="text-2xl font-black text-slate-400 transition hover:text-[#3369A1] focus:text-[#3369A1] focus:outline-none">&times;</button>
                </div>
                <!-- <ul class="mt-2 space-y-1 rounded-md border border-slate-300 bg-white p-2">
                    <template x-for="(layer, index) in map.getAllLayers().reverse()" :key="index">
                        <li class="flex items-center px-2 py-1">
                            <div x-id="['legend-range']" class="w-full">
                                <label x-bind:for="$id('legend-range')" class="flex items-center">
                                    <span class="text-sm text-slate-600" x-text="layer.get('label')"></span>
                                </label>
                                <div class="mt-1 text-sm text-slate-600">
                                    <input class="w-full accent-[#3369A1]"
                                            type="range"
                                            min="0"
                                            max="1"
                                            step="0.01"
                                            x-bind:id="$id('legend-range')"
                                            x-bind:value="layer.getOpacity()"
                                            x-on:change="layer.setOpacity(Number($event.target.value))">
                                </div>
                            </div>
                        </li>
                    </template>
                </ul> -->
                <ul>
                    <template x-for="(layer, index) in map.getAllLayers().reverse()" :key="index">
                        <li class="flex items-center px-2 py-1">
                            <div x-id="['legend-checkbox']">
                                <label x-bind:for="$id('legend-checkbox')" class="flex items-center">
                                    <input type="checkbox" x-bind:checked="layer.getVisible()"
                                        x-bind:id="$id('legend-checkbox')"
                                        x-on:change="layer.setVisible(!layer.getVisible())"
                                        class="rounded border-slate-300 text-[#3369A1] shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-slate-600" x-text="layer.get('label')"></span>
                                </label>
                                <template x-if="layer.get('label') === 'Monuments' && layer.getVisible()">
                                    <div class="mt-2 ml-6 text-sm text-slate-600">
                                        <template x-for="(feature, index) in layer.getSource().getFeatures()"
                                            :key="index">
                                            <a href="#" :title="'Go to '  feature.get('name')"
                                                x-text="feature.get('name')" x-on:click.prevent="gotoFeature(feature)"
                                                class="block hover:underline hover:text-slate-800 focus:outline-none focus:underline focus:text-slate-800 transition">
                                            </a>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </li>
                    </template>
                </ul>
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
