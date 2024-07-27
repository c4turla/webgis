import Map from 'ol/Map.js';
import View from 'ol/View.js';
import TileLayer from 'ol/layer/Tile.js';
import VectorSource from 'ol/source/Vector';
import VectorLayer from 'ol/layer/Vector';
import OSM from 'ol/source/OSM.js';
import GeoJSON from 'ol/format/GeoJSON';
import Overlay from 'ol/Overlay.js';
import { Style, Fill, Stroke, Circle, Text } from 'ol/style.js';
import XYZ from 'ol/source/XYZ.js';
import { FullScreen, defaults as defaultControls, OverviewMap } from 'ol/control.js';
import MousePosition from 'ol/control/MousePosition';
import { fromLonLat, toLonLat } from 'ol/proj';
import {
  DragRotateAndZoom,
  defaults as defaultInteractions,
} from 'ol/interaction.js';

// Create a custom coordinate format function
const customCoordinateFormat = (coordinate) => {
  // Convert to longitude and latitude
  const lonLat = toLonLat(coordinate);

  return `Latitude: ${lonLat[1].toFixed(4)}N<br>Longitude: ${lonLat[0].toFixed(4)}E`;
};
document.addEventListener('alpine:init', () => {
    Alpine.data('map', function () {
      return {
        legendOpened: false,
        map: null,
        activeLayer: 'OSM',
        features: [], // Initialize features as an empty array
        initComponent(monuments) {
          // Initialize map components
          this.updateFeatures(monuments); // Initialize with provided features
  
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
              rotation: -Math.PI / 8,
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
            interactions: defaultInteractions().extend([new DragRotateAndZoom()]),
          });
  
          this.map.on('singleclick', (event) => {
            if (event.dragging) return;
            let overlay = this.map.getOverlayById('info');
            overlay.setPosition(undefined);
            this.$refs.popupContent.innerHTML = '';
            this.map.forEachFeatureAtPixel(event.pixel, (feature, layer) => {
              if (layer.get('label') === 'Monuments' && feature) {
                this.gotoFeature(feature);
                let content =
                  '<h4 class="text-gray-500 font-bold">' +
                  feature.get('name') +
                  '</h4>';
                content +=
                  '<img src="' +
                  feature.get('image') +
                  '" class="mt-2 w-full max-h-[200px] rounded-md shadow-md object-contain overflow-clip">';
                this.$refs.popupContent.innerHTML = content;
                setTimeout(() => {
                  overlay.setPosition(feature.getGeometry().getCoordinates());
                }, 500);
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
              if (kategoriId) {
                fetch(`/filter-kategori/${kategoriId}`, {
                  method: 'GET',
                  headers: {
                    'X-CSRF-TOKEN': token
                  }
                })
                .then(response => response.json())
                .then(data => {
                  console.log(data);
                  this.updateFeatures(data); // Call updateFeatures with new data
                })
                .catch(error => console.error('Error fetching data:', error));
              }
            });
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
          return new Style({
            image: new Circle({
              radius: 4,
              fill: new Fill({
                color: 'rgba(0, 255, 255, 1)',
              }),
              stroke: new Stroke({
                color: 'rgba(192, 192, 192, 1)',
                width: 2,
              }),
            }),
            text: new Text({
              font: '12px sans-serif',
              textAlign: 'left',
              text: feature.get('name'),
              offsetY: -15,
              offsetX: 5,
              backgroundFill: new Fill({
                color: 'rgba(255, 255, 255, 0.5)',
              }),
              backgroundStroke: new Stroke({
                color: 'rgba(227, 227, 227, 1)',
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
            zoom: 15,
            duration: 500,
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
  
  