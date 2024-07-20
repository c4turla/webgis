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
import { fromLonLat } from 'ol/proj.js';
import { FullScreen, defaults as defaultControls, OverviewMap } from 'ol/control.js';
import {
  DragRotateAndZoom,
  defaults as defaultInteractions,
} from 'ol/interaction.js';

document.addEventListener('alpine:init', () => {
    Alpine.data('map', function () {
        return {
            legendOpened: false,
            map: {},
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

                const vectorLayer = new VectorLayer({
                    source: new VectorSource({
                        features: this.features,
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
                    controls: defaultControls().extend([ this.overviewMapControl, new FullScreen()]),
                    target: this.$refs.map,
                    layers: [osmLayer, arcgisLayer, xyzLayer, vectorLayer],
                    view: new View({
                        projection: 'EPSG:3857',
                        rotation: -Math.PI / 8,
                        center: fromLonLat([122.0591579, -3.9084353]), // Convert coordinates
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
                document.getElementById('toggle-overview').addEventListener('click', () => {
                    const isCollapsed = this.overviewMapControl.getCollapsed();
                    this.overviewMapControl.setCollapsed(!isCollapsed);
                });
                this.map.on('singleclick', (event) => {
                    if (event.dragging) {
                        return;
                    }
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
                // Method to switch layers
                this.switchLayer = (layerName) => {
                    this.map.getLayers().getArray().forEach((layer) => {
                        if (
                            layer.get('label') === 'OpenStreetMap' ||
                            layer.get('label') === 'ArcGIS World Topo Map' ||
                            layer.get('label') === 'XYZ World Topo Map' ||
                            layer.get('label') === 'Monuments'
                        ) {
                            layer.setVisible(layer.get('label') === layerName);
                        }
                    });
                };

                // Initial layer set to OSM
                this.switchLayer('OpenStreetMap');
            },
            closePopup() {
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
                const map = this.map; // Assuming you have a reference to the map object here
                map.once('rendercomplete', () => {
                    const mapCanvas = document.createElement('canvas');
                    const size = map.getSize();
                    mapCanvas.width = size[0];
                    mapCanvas.height = size[1];
                    const mapContext = mapCanvas.getContext('2d');
                    Array.prototype.forEach.call(
                        map.getViewport().querySelectorAll('.ol-layer canvas, canvas.ol-layer'),
                        (canvas) => {
                            if (canvas.width > 0) {
                                const opacity = canvas.parentNode.style.opacity || canvas.style.opacity;
                                mapContext.globalAlpha = opacity === '' ? 1 : Number(opacity);
                                let matrix;
                                const transform = canvas.style.transform;
                                if (transform) {
                                    // Get the transform parameters from the style's transform matrix
                                    matrix = transform.match(/^matrix\(([^\(]*)\)$/)[1].split(',').map(Number);
                                } else {
                                    matrix = [
                                        parseFloat(canvas.style.width) / canvas.width,
                                        0,
                                        0,
                                        parseFloat(canvas.style.height) / canvas.height,
                                        0,
                                        0
                                    ];
                                }
                                // Apply the transform to the export map context
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
                map.renderSync();
            },  
            zoomIn() {
                let view = this.map.getView();
                let zoom = view.getZoom();
                view.setZoom(zoom + 1);
            },
            zoomOut() {
                let view = this.map.getView();
                let zoom = view.getZoom();
                view.setZoom(zoom - 1);
            }
        };
    });
});
