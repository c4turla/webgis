@extends('admin.layouts.master')

@section('content')
<div
    class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

        <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
            <div class="grow">
                <h5 class="text-16">Edit Tempat Failitas Olahraga</h5>
            </div>
            <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                <li
                    class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                    <a href="#!" class="text-slate-400 dark:text-zink-200">Data Lokasi</a>
                </li>
                <li class="text-slate-700 dark:text-zink-100">
                    Edit Tempat Failitas Olahraga
                </li>
            </ul>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-x-5">
            <div class="xl:col-span-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 text-15">Edit Lokasi</h6>

                        <form action="{{ route('fasilitas-olahraga.update',$folahraga->id_tempat) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2 xl:grid-cols-12">
                                <div class="xl:col-span-6">
                                    <label for="nama_tempat" class="inline-block mb-2 text-base font-medium">Nama
                                        Tempat</label>
                                    <input type="text" id="nama_tempat" name="nama_tempat"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Nama Tempat" required="" value="{{ $folahraga->nama_tempat }}">
                                </div>
                                <!--end col-->
                                <div class="xl:col-span-6">
                                    <label for="kategori"
                                        class="inline-block mb-2 text-base font-medium">Kategori</label>
                                    <input type="text" id="kategori"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Kategori" value="Tempat Failitas Olahraga" disabled="">
                                    <p class="mt-1 text-sm text-slate-400 dark:text-zink-200">Kategori dipilih secara
                                        otomatis</p>
                                </div>
                                <div class="xl:col-span-12">
                                    <label for="alamat" class="inline-block mb-2 text-base font-medium">Alamat</label>
                                    <input type="text" id="alamat" name="alamat"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Alamat Failitas Olahraga" value="{{ $folahraga->alamat }}" required="">
                                    <p class="mt-2 invisible peer-invalid:visible text-pink-600 text-sm">
                                        Please provide a valid email address.
                                    </p>
                                </div>
                                <!--end col-->
                                <div class="lg:col-span-2 xl:col-span-12">
                                    <div>
                                        <label for="deskripsi"
                                            class="inline-block mb-2 text-base font-medium">Deskripsi</label>
                                        <textarea name="deskripsi"
                                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                            id="deskripsi" placeholder="Masukan deskripsi lokasi"
                                            rows="5">{{ $folahraga->deskripsi }}</textarea>
                                    </div>
                                </div>
                                <div class="xl:col-span-4">
                                    <label for="harga_tiket" class="inline-block mb-2 text-base font-medium">Harga Tiket
                                        Masuk</label>
                                    <input type="number" id="harga_tiket" name="harga_tiket"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Harga Tiket" value="{{ $folahraga->harga_tiket }}">
                                </div>
                                <!--end col-->
                                <div class="xl:col-span-4">
                                    <label for="jam_buka" class="inline-block mb-2 text-base font-medium">Jam
                                        Buka</label>
                                    <input type="time" id="jam_buka" name="jam_buka"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        value="{{ $folahraga->jam_buka }}">
                                </div>
                                <!--end col-->
                                <div class="xl:col-span-4">
                                    <label for="jam_tutup" class="inline-block mb-2 text-base font-medium">Jam
                                        Tutup</label>
                                    <input type="time" id="jam_tutup" name="jam_tutup"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        value="{{ $folahraga->jam_tutup }}">
                                </div>
                                <!--end col-->
                                <div class="xl:col-span-4">
                                    <label for="fasilitas"
                                        class="inline-block mb-2 text-base font-medium">Fasilitas</label>
                                    <input type="text" id="fasilitas" name="fasilitas"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Fasilitas" value="{{ $folahraga->fasilitas }}">
                                </div>
                                <!--end col-->
                                <div class="xl:col-span-4">
                                    <label for="kontak" class="inline-block mb-2 text-base font-medium">Kontak</label>
                                    <input type="text" id="kontak" name="kontak"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Kontak" value="{{ $folahraga->kontak }}">
                                </div>
                                <div class="xl:col-span-4">
                                    <label for="status" class="inline-block mb-2 text-base font-medium">Status</label>
                                    <select
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        data-choices="" data-choices-search-false="" name="status" id="status">
                                        <option value="">Pilih Status</option>
                                        <option value="aktif" {{  $folahraga->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{  $folahraga->status == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                                    </select>
                                </div>

                                <!--end col-->
                                <div class="xl:col-span-6">
                                    <label for="latitude"
                                        class="inline-block mb-2 text-base font-medium">Latitude</label>
                                    <input type="text" id="latitude" name="latitude"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Latitude" value="{{ $folahraga->latitude }}">
                                </div>
                                <!--end col-->
                                <div class="xl:col-span-6">
                                    <label for="longitude"
                                        class="inline-block mb-2 text-base font-medium">Longitude</label>
                                    <input type="text" id="longitude" name="longitude"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Longitude" value="{{ $folahraga->longitude }}">
                                </div>
                                <div class="lg:col-span-2 xl:col-span-12">
                                    <label for="peta" class="inline-block mb-2 text-base font-medium">Lokasi Tempat
                                        Failitas Olahraga</label>
                                    <div id="map" class="w-full" style="height: 450px;"> </div>

                                </div>

                                <div class="col-span-12">
                                    <label class="inline-block mb-2 text-base font-medium">Foto Tempat</label>
                                    <!-- Container with grid layout -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($folahraga->foto as $foto)
                                        <div class="relative flex flex-col rounded-md border-2 {{ $foto->is_utama ? 'border-sky-500' : 'border-gray-200' }} border-dashed bg-white text-gray-900 transition-all duration-100 ease-linear sm:h-auto p-1 w-full max-w-xs">
                                            <a class="flex flex-col no-underline">
                                                <div class="relative p-1 flex justify-center items-center overflow-hidden">
                                                    <img src="{{ asset('storage/' . $foto->path) }}" alt="{{$foto->id_foto}}" class="w-full h-64 rounded-md object-cover filter grayscale-50 transition-all duration-300 ease-in-out hover:scale-110 hover:grayscale-0">
                                                </div>
                                            </a>
                                            <div class="absolute top-1 left-1 z-10 bg-sky-400 py-1 px-2 rounded">
                                                <label class="flex items-center space-x-2">
                                                    <input 
                                                        type="checkbox" 
                                                        {{ $foto->is_utama ? 'checked' : '' }} 
                                                        onclick="updateIsUtama('{{ $foto->id_foto }}')" 
                                                        class="form-checkbox h-5 w-5 text-sky-500 dark:text-sky-500"
                                                    >
                                                    <span class="text-white dark:text-gray-200">
                                                        Utama
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="absolute top-1 right-1 z-10">
                                                <button 
                                                    type="button" 
                                                    class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 remove-item-btn bg-red-100 text-red-500 hover:text-red-900 hover:bg-red-200 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-red-500/20 dark:hover:text-red-500"
                                                    data-id="{{ $foto->id_foto }}"
                                                    onclick="showDeleteModal(this)"
                                                >
                                                    <i data-lucide="trash-2" class="size-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="rounded-lg shadow-lg col-span-12 bg-white py-6 border-dashed  border-2 border-slate-500">
                                    <div class="flex flex-col items-center justify-center border-3 border-dotted border-gray-400 rounded-md">
                                            <h4 class="text-lg font-normal text-gray-800">Upload File Gambar Disini</h4>
                                        <p class="text-xs text-gray-400">Files Supported: PNG, JPG, JPEG</p>
                                        <input type="file" name="foto[]" hidden accept=".png,.jpg,.jpeg" id="fileID" multiple>
                                        <a type="button" id="uploadButton"  class="text-white cursor-pointer bg-slate-400 border-slate-500 btn hover:text-white hover:bg-slate-600 hover:border-slate-600 focus:text-white mt-2 focus:bg-slate-600 focus:border-slate-600 focus:ring focus:ring-slate-100 active:text-white active:bg-slate-600 active:border-slate-600 active:ring active:ring-slate-100 dark:ring-slate-400/10 transition-colors">Pilih Gambar</a>
                                    </div>
                                    <div class="flex justify-center items-center">
                                        <div id="preview" class="mt-4 flex flex-wrap gap-4"></div>
                                    </div>
                                </div>
                            </div>
                            <!--end grid-->
                            <div class="flex justify-end gap-2 mt-4 ">
                                <button type="submit"
                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Simpan
                                    Tempat Failitas Olahraga</button>
                                <a type="button" href="{{ route('fasilitas-olahraga') }}"
                                    class="text-white bg-green-500 border-green-500 btn hover:text-white hover:bg-green-600 hover:border-green-600 focus:text-white focus:bg-green-600 focus:border-green-600 focus:ring focus:ring-green-100 active:text-white active:bg-green-600 active:border-green-600 active:ring active:ring-green-100 dark:ring-green-400/10">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center z-50 hidden mx-6">
    <div class="modal-overlay absolute inset-0 bg-gray-900 opacity-50"></div>
    <div class="modal-container bg-white dark:bg-zink-800 md:w-1/3 mx-4 md:mx-auto rounded shadow-lg z-50 overflow-y-auto px-6">
        <div class="modal-body py-4 text-center">
            <h2 class="text-lg font-bold mb-4">Konfirmasi Hapus</h2>
            <p class="text-gray-600 dark:text-zink-400">Apakah anda yakin menghapus foto ini?</p>
            <div class="flex justify-center gap-4 mt-4">
                <form id="deleteForm" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">Hapus</button>
                </form>
                <button type="button" class="btn bg-gray-300 text-gray-700 hover:bg-gray-400 dark:bg-zink-600 dark:text-zink-100 dark:hover:bg-zink-700 cancel-btn">Batal</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="{{ URL::to('assets/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ URL::to('assets/js/pages/apps-ecommerce-product-create.init.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
            var lat = @json($folahraga->latitude ?? -3.8087);
            var lng = @json($folahraga->longitude ?? 122.3060);
            var zoomLevel = 10;

            // Inisialisasi peta
            var map = L.map('map').setView([lat, lng], zoomLevel);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker
        var nama = @json($folahraga->nama_tempat ?? 'Tempat Failitas Olahraga!');
        var marker = L.marker([lat, lng], { draggable: true }).addTo(map)
                .bindPopup(`<b>${nama}</b>`).openPopup();

        // Update input fields when the marker is dragged
        marker.on('dragend', function (e) {
            var latlng = marker.getLatLng();
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;
        });

        // Update marker position and input fields on map click
        map.on('click', function(e) {
            var latlng = e.latlng;
            marker.setLatLng(latlng);
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;
        });

        // Initialize input fields with marker position
        var initialLatLng = marker.getLatLng();
        document.getElementById('latitude').value = initialLatLng.lat;
        document.getElementById('longitude').value = initialLatLng.lng;
    });

    function showDeleteModal(button) {
        const fotoId = button.getAttribute('data-id');
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');

        const deleteForm = modal.querySelector('#deleteForm');
        deleteForm.action = `{{ route('foto.destroy', '') }}/${fotoId}`;

        const cancelButton = modal.querySelector('.cancel-btn');
        cancelButton.onclick = function() {
            modal.classList.add('hidden');
        };
    }
    function updateIsUtama(fotoId) {
        let isChecked = event.target.checked ? 1 : null;
        
        fetch(`/admin/foto/${fotoId}/is-utama`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                is_utama: isChecked
            })
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload(); 
        })
        .catch(error => {
            window.location.reload(); 
        });
    }

    document.getElementById('uploadButton').addEventListener('click', function() {
        document.getElementById('fileID').click();
    });

    document.getElementById('fileID').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('preview');
        previewContainer.innerHTML = ''; 

        for (const file of files) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-24 h-24 object-cover border rounded-md'; 
                previewContainer.appendChild(img);
            }

            reader.readAsDataURL(file);
        }
    });
</script>


@endpush