import Map from 'ol/Map.js';
import View from 'ol/View.js';
import TileLayer from 'ol/layer/Tile.js';
import VectorSource from 'ol/source/Vector';
import VectorLayer from 'ol/layer/Vector';
import OSM from 'ol/source/OSM.js';
import GeoJSON from 'ol/format/GeoJSON';
import Overlay from 'ol/Overlay.js';
import { Style, Fill, Stroke, Circle, Text, Icon } from 'ol/style.js';
import XYZ from 'ol/source/XYZ.js';
import { FullScreen, defaults as defaultControls, OverviewMap } from 'ol/control.js';
import MousePosition from 'ol/control/MousePosition';
import { fromLonLat, toLonLat } from 'ol/proj';
import { defaults as defaultInteractions } from 'ol/interaction.js';

const kategoriIcons = {
  1: '/assets/images/map/markerRed.png',
  2: '/assets/images/map/markerYellow.png',
  3: '/assets/images/map/markerGreen.png',
};

const customCoordinateFormat = (coordinate) => {
  const lonLat = toLonLat(coordinate);

  return `Latitude: ${lonLat[1].toFixed(4)}N<br>Longitude: ${lonLat[0].toFixed(4)}E`;
};

document.addEventListener('alpine:init', () => {
  Alpine.data('map', function () {
    return {
      legendOpened: false,
      map: null,
      activeLayer: 'OSM',
      initComponent(monuments) {

        this.features = new GeoJSON().readFeatures(monuments, {
          featureProjection: 'EPSG:3857',
        });

        const osmLayer = new TileLayer({
          source: new OSM(),
          label: 'OpenStreetMap',
        });

        const arcgisLayer = new TileLayer({
          source: new XYZ({
            attributions: 'Tiles © <a href="https://services.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer">ArcGIS</a>',
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}',
          }),
          label: 'ArcGIS World Topo Map',
        });

        const xyzLayer = new TileLayer({
          source: new XYZ({
            url: 'https://{a-c}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey=0e6fc415256d4fbb9b5166a718591d71',
          }),
          label: 'XYZ World Topo Map',
        });

        this.vectorLayer = new VectorLayer({
          source: new VectorSource({
            features: this.features, // Set initial features
          }),
          style: this.styleFunction,
          label: 'Monuments',
        });

        this.overviewMapControl = new OverviewMap({
          layers: [
            new TileLayer({
              source: new OSM(),
            }),
          ],
          collapsed: true,
        });

        this.map = new Map({
          controls: defaultControls().extend([
            this.overviewMapControl,
            new FullScreen(),
            new MousePosition({
              coordinateFormat: customCoordinateFormat,
              projection: 'EPSG:3857',
              className: 'custom-mouse-position',
              target: document.getElementById('mouse-position'),
              undefinedHTML: '&nbsp;',
            }),
          ]),
          target: this.$refs.map,
          layers: [osmLayer, arcgisLayer, xyzLayer, this.vectorLayer],
          view: new View({
            projection: 'EPSG:3857',
            center: fromLonLat([122.0591579, -3.9084353]),
            zoom: 10,
          }),
          overlays: [
            new Overlay({
              id: 'info',
              element: this.$refs.popup,
              stopEvent: true,
            }),
          ],
          interactions: defaultInteractions(),
        });

        this.map.on('singleclick', (event) => {
          if (event.dragging) return;
          let overlay = this.map.getOverlayById('info');
          overlay.setPosition(undefined);
          this.$refs.popupContent.innerHTML = '';
      
          this.map.forEachFeatureAtPixel(event.pixel, (feature, layer) => {
              if (layer.get('label') === 'Monuments' && feature) {
                  let id = feature.get('id'); // Assuming the feature has an 'id' property
                  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                  const token = tokenMeta.getAttribute('content');
                  fetch(`/detail-tempat/${id}`, {
                    method: 'GET',
                    headers: {
                      'X-CSRF-TOKEN': token,
                    }
                  })
                      .then(response => response.json())
                      .then(data => {
                            let images = data.foto.map(f => f.path);
                            let imagesArray = JSON.stringify(images);
                            let content =
                            '<div class="bg-white shadow-xl rounded-lg flex flex-col">' +
                              '<div x-data=\'imageSlider(' + imagesArray + ')\' class="w-full max-h-[200px] rounded-md shadow-md object-contain overflow-clip">' +
                                '<div class="absolute right-5 top-10 z-10 rounded-full bg-gray-600 px-2 text-center text-sm text-white">' +
                                  '<span x-text="currentIndex"></span>/<span x-text="images.length"></span>' +
                                '</div>' +
                                '<button @click="previous()" class="absolute left-5 top-1/4 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-gray-100 shadow-md">' +
                                  '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">' +
                                    '<path d="M14 7l-5 5 5 5V7z"/>' +
                                    '<path d="M0 0h24v24H0z" fill="none"/>' +
                                  '</svg>' +
                                '</button>' +
                                '<button @click="forward()" class="absolute right-5 top-1/4 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-gray-100 shadow-md">' +
                                  '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">' +
                                    '<path d="M10 17l5-5-5-5v10z"/>' +
                                    '<path d="M0 0h24v24H0z" fill="none"/>' +
                                  '</svg>' +
                                '</button>' +
                                '<div class="flex flex-col">' +
                                  '<template x-for="(image, index) in images">' +
                                    '<div x-show="currentIndex == index + 1" x-transition:enter="transition transform duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition transform duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">' +
                                      '<img :src="image" alt="image" class="w-full max-h-[200px] rounded-md shadow-md object-cover overflow-clip " />' +
                                    '</div>' +
                                  '</template>' +
                                '</div>' +
                              '</div>' +
                              '<div class="p-2 flex-col flex gap-2">' +
                                '<p class="uppercase tracking-wide text-sm font-bold text-gray-700">' + feature.get('name') + '</p>' +
                                '<p class="text-gray-700 text-sm flex flex-row gap-2 justify-start items-center">' +
                                  '<svg width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">' +
                                    '<path d="M6.62 10.79a15.09 15.09 0 006.59 6.59l2.2-2.2a1 1 0 011.05-.24 11.36 11.36 0 003.58.56 1 1 0 011 1v3.17a1 1 0 01-1 1A16 16 0 013 4a1 1 0 011-1h3.16a1 1 0 011 1 11.36 11.36 0 00.57 3.58 1 1 0 01-.25 1.05z" fill="currentColor"/>' +
                                  '</svg>' +
                                  feature.get('kontak') + 
                                '</p>' +
                                '<p class="text-gray-700 text-sm flex flex-row gap-2 justify-start items-center">' +
                                  '<svg width="16" height="16" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">' +
                                    '<circle cx="50" cy="50" r="45" stroke="black" stroke-width="5" fill="none" />' +
                                    '<line x1="50" y1="50" x2="50" y2="15" stroke="black" stroke-width="3" />' +
                                  '</svg>' +
                                  feature.get('waktu') +
                                '</p>' +
                              '</div>' +
                              '<div class="p-2 border-t border-gray-300 bg-gray-100">' +
                                '<div class="flex items-center pt-2">' +
                                  '<div>' +
                                    '<p class="text-sm text-gray-700 max-h-20 overflow-y-auto">' +
                                      feature.get('deskripsi') +
                                    '</p>' +
                                  '</div>' +
                                '</div>' +
                              '</div>' +
                            '</div>';
                            this.$refs.popupContent.innerHTML = content;
                            setTimeout(() => {
                                overlay.setPosition(feature.getGeometry().getCoordinates());
                            }, 500);
                        })
                        .catch(error => console.error('Error:', error));
                    return;
                }
            }, {
                hitTolerance: 5,
            });
        });
        
        this.map.on('loadstart', function () {
          this.getTargetElement().classList.add('spinner');
        });
        this.map.on('loadend', function () {
          this.getTargetElement().classList.remove('spinner');
        });

        this.switchLayer(['Monuments', 'OpenStreetMap']);

        // DOMContentLoaded Logic
        document.addEventListener('DOMContentLoaded', () => {
          // Handle category selection
          const selectElement = document.getElementById('kategori-select');
          const tokenMeta = document.querySelector('meta[name="csrf-token"]');
          const token = tokenMeta.getAttribute('content');
          selectElement.addEventListener('change', () => {
            const kategoriId = selectElement.value;
            fetch(`/filter-kategori/${kategoriId}`, {
              method: 'GET',
              headers: {
                'X-CSRF-TOKEN': token
              }
            })
            .then(response => response.json())
            .then(data => {
              this.updateFeatures(data); // Call updateFeatures with new data
            })
            .catch(error => console.error('Error fetching data:', error));
          });
        });

        this.map.on('pointermove', (event) => {
          if (event.dragging) {
            return;
          }
          const pixel = this.map.getEventPixel(event.originalEvent);
          const hit = this.map.hasFeatureAtPixel(pixel);
          this.map.getTarget().style.cursor = hit ? 'pointer' : '';
        });
      },
      updateFeatures(monuments) {
        if (!this.map) {
          console.error('Map is not initialized');
          return;
        }

        // Update features
        this.features = new GeoJSON().readFeatures(monuments, {
          featureProjection: 'EPSG:3857',
        });

        // Update vector layer source
        const vectorSource = this.vectorLayer.getSource();
        vectorSource.clear(); // Clear existing features
        vectorSource.addFeatures(this.features); // Add new features
      },
      switchLayer(layerNames) {
        if (!this.map) {
          console.error('Map is not initialized');
          return;
        }
        if (!Array.isArray(layerNames)) {
          layerNames = [layerNames];
        }

        this.map.getLayers().getArray().forEach((layer) => {
          if (
            layer.get('label') === 'OpenStreetMap' ||
            layer.get('label') === 'ArcGIS World Topo Map' ||
            layer.get('label') === 'XYZ World Topo Map' ||
            layer.get('label') === 'Monuments'
          ) {
            layer.setVisible(layerNames.includes(layer.get('label')));
          }
        });
      },   
      switchToOSM() {
        this.switchLayer(['Monuments', 'OpenStreetMap']);
      },
      switchToArcGIS() {
        this.switchLayer(['Monuments', 'ArcGIS World Topo Map']);
      },
      switchToXYZ() {
        this.switchLayer(['Monuments', 'XYZ World Topo Map']);
      },
      closePopup() {
        if (!this.map) {
          console.error('Map is not initialized');
          return;
        }
        let overlay = this.map.getOverlayById('info');
        overlay.setPosition(undefined);
        this.$refs.popupContent.innerHTML = '';
      },
      
      styleFunction(feature, resolution) {
        const kategoriId = feature.get('kategori');
        const iconUrl = kategoriIcons[kategoriId] || '/assets/images/map/markerRed.png';
        let textColor;
        switch (kategoriId) {
          case 1:
            textColor = 'rgba(255, 0, 0, 0.8)';
            break;
          case 2:
            textColor = 'rgba(255, 255, 0, 0.8)';
            break;
          case 3:
            textColor = 'rgba(0, 128, 0, 0.8)';
            break;
          default:
            textColor = 'rgba(255, 255, 255, 0.8)';
            break;
        }
        return new Style({
          image: new Icon({
            src: iconUrl,
            scale: 0.06,
            anchor: [0.5, 1],
          }),
          text: new Text({
            font: '12px sans-serif',
            textAlign: 'center',
            text: feature.get('name'),
            offsetY: -45,
            fill: new Fill({
              color: textColor,
            }),
            stroke: new Stroke({
              color: 'rgba(255, 255, 255, 0.7)',
              width: 1,
            }),
            backgroundFill: new Fill({
              color: 'rgba(255, 255, 255, 0)',
            }),
            backgroundStroke: new Stroke({
              color: 'rgba(255, 255, 255, 0)',
            }),
            padding: [5, 2, 2, 5],
          }),
        });
      },
      gotoFeature(feature) {
        if (!this.map) {
          console.error('Map is not initialized');
          return;
        }
        this.map.getView().animate({
          center: feature.getGeometry().getCoordinates(),
          zoom: 10,
          duration: 1000,
        });
      },
      toggleFullScreen() {
        if (!document.fullscreenElement) {
          document.documentElement.requestFullscreen();
        } else {
          if (document.exitFullscreen) {
            document.exitFullscreen();
          }
        }
      },
      download() {
        this.$nextTick(() => {
          this.downloadMap();
        });
      },
      downloadMap() {
        if (!this.map) {
          console.error('Map is not initialized');
          return;
        }
        this.map.once('rendercomplete', () => {
          const mapCanvas = document.createElement('canvas');
          const size = this.map.getSize();
          mapCanvas.width = size[0];
          mapCanvas.height = size[1];
          const mapContext = mapCanvas.getContext('2d');
          Array.prototype.forEach.call(
            this.map.getViewport().querySelectorAll('.ol-layer canvas, canvas.ol-layer'),
            (canvas) => {
              if (canvas.width > 0) {
                const opacity = canvas.parentNode.style.opacity || canvas.style.opacity;
                mapContext.globalAlpha = opacity === '' ? 1 : Number(opacity);
                let matrix;
                const transform = canvas.style.transform;
                if (transform) {
                  matrix = transform.match(/^matrix\(([^\(]*)\)$/)[1].split(',').map(Number);
                } else {
                  matrix = [
                    parseFloat(canvas.style.width) / canvas.width,
                    0,
                    0,
                    parseFloat(canvas.style.height) / canvas.height,
                    0,
                    0,
                  ];
                }
                CanvasRenderingContext2D.prototype.setTransform.apply(mapContext, matrix);
                const backgroundColor = canvas.parentNode.style.backgroundColor;
                if (backgroundColor) {
                  mapContext.fillStyle = backgroundColor;
                  mapContext.fillRect(0, 0, canvas.width, canvas.height);
                }
                mapContext.drawImage(canvas, 0, 0);
              }
            }
          );
          mapContext.globalAlpha = 1;
          mapContext.setTransform(1, 0, 0, 1, 0, 0);
          const link = document.createElement('a');
          link.download = 'map.png';
          link.href = mapCanvas.toDataURL();
          link.click();
        });
        this.map.renderSync();
      },
      zoomIn() {
        if (!this.map) {
          console.error('Map is not initialized');
          return;
        }
        let view = this.map.getView();
        let zoom = view.getZoom();
        view.setZoom(zoom + 1);
      },
      zoomOut() {
        if (!this.map) {
          console.error('Map is not initialized');
          return;
        }
        let view = this.map.getView();
        let zoom = view.getZoom();
        view.setZoom(zoom - 1);
      },
    };
  });
});
