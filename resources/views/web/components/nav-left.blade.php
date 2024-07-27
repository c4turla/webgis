<nav class="nav transition-transform bg-primary-800 transform bg-gray-800" id="sidebar">
    <div class="px-3 py-2 cursor-pointer">
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
    </div>
</nav>
<script>
    function toggleNav() {
        const sidebar = document.getElementById('sidebar');
        sidebar.style.width = sidebar.style.width === '0px' || sidebar.style.width === '' ? '300px' : '0px';
    }
</script>
