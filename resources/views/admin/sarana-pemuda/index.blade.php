@extends('admin.layouts.master')

@section('content')
    {{-- message --}}
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Tempat Sarana Pemuda</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Data Lokasi</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Tempat Sarana Pemuda
                    </li>
                </ul>
            </div>
            <div class="grid grid-cols-12 2xl:grid-cols-12 gap-x-5">
                <div class="col-span-12 card">
                    <div class="card-body">
                        <div class="flex items-center gap-3 mb-4">
                            <h6 class="text-15 grow">Data Tempat Sarana Pemuda</h6>
                        </div>
                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-12">
                            <div class="xl:col-span-3">
                                <form method="GET" action="{{ route('sarana-pemuda') }}">
                                    <div class="relative">
                                        <input type="text" name="search" value="{{ request('search') }}" class="ltr:pl-8 rtl:pr-8 search form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="Cari Tempat Sarana Pemuda ..." autocomplete="off">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="search" class="lucide lucide-search inline-block size-4 absolute ltr:left-2.5 rtl:right-2.5 top-2.5 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-600"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>                                    
                                    </div>
                                </form>
                            </div><!--end col-->
                            <div class="xl:col-span-2 xl:col-start-11">
                                <div class="ltr:lg:text-right rtl:lg:text-left">
                                    <a href="{{ route('sarana-pemuda.add') }}" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="plus" class="lucide lucide-plus inline-block size-4"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg> <span class="align-middle">Tambah Tempat</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full whitespace-nowrap">
                            <thead class="ltr:text-left rtl:text-right bg-slate-100 text-slate-500 dark:text-zink-200 dark:bg-zink-600">
                                <tr>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 text-base border-y border-slate-200 dark:border-zink-500">Nama Tempat</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 text-base border-y border-slate-200 dark:border-zink-500">Deskripsi</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 text-base border-y border-slate-200 dark:border-zink-500">Alamat</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 text-base border-y border-slate-200 dark:border-zink-500">Lat</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 text-base border-y border-slate-200 dark:border-zink-500">Long</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 text-base border-y border-slate-200 dark:border-zink-500">Status</th>
                                    <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 text-base border-y border-slate-200 dark:border-zink-500">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($spemuda as $sarana)
                                <tr>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y text-base border-slate-200 dark:border-zink-500">{{ $sarana->nama_tempat }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y text-base border-slate-200 dark:border-zink-500">{{ \Illuminate\Support\Str::limit($sarana->deskripsi, 45) }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y text-base border-slate-200 dark:border-zink-500">{{ \Illuminate\Support\Str::limit($sarana->alamat, 20) }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y text-base border-slate-200 dark:border-zink-500">{{ $sarana->latitude }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y text-base border-slate-200 dark:border-zink-500">{{ $sarana->longitude }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y text-base border-slate-200 dark:border-zink-500">{{ $sarana->status }}</td>
                                    <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                        <div class="flex gap-3">
                                            <a href="{{ route('sarana-pemuda.edit', $sarana->id_tempat) }}" class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 edit-item-btn bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500"><i data-lucide="pencil" class="size-4"></i></a>
                                            <button type="button" onclick="openDeleteModal('{{ $sarana->id_tempat }}')" class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 remove-item-btn bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-red-500/20 dark:hover:text-red-500"><i data-lucide="trash-2" class="size-4"></i></button>
                                             <!-- Hidden Delete Form -->
                                             
                                            <form id="delete-form-{{ $sarana->id_tempat }}" action="{{ route('sarana-pemuda.destroy', ['id' => $sarana->id_tempat ]) }}" method="POST"  class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>            
                                    <td colspan="7" class="px-3.5 py-6 text-center text-lg border-y text-base border-slate-200 dark:border-zink-500">
                                        Data Tempat Sarana Pemuda Masih Kosong.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-col items-center gap-4 px-4 mt-4 mb-5 md:flex-row" id="pagination-element">
                        <div class="grow">
                            <p class="text-slate-500 dark:text-zink-200">Showing <b class="showing">{{ $spemuda->count() }}</b> of <b class="total-records">{{ $spemuda->total() }}</b> Results</p>
                        </div>

                        <div class="col-sm-auto mt-sm-0">
                            {{ $spemuda->appends(request()->query())->links('vendor.pagination.tailwind-custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 flex items-center justify-center z-50 hidden mx-6">
        <div class="modal-overlay absolute inset-0 bg-gray-900 opacity-50"></div>
        <div class="modal-container bg-white dark:bg-zink-800 md:w-1/3 mx-4 md:mx-auto rounded shadow-lg z-50 overflow-y-auto px-6">
            <div class="modal-body py-4 text-center">
                <h2 class="text-lg font-bold mb-4">Konfirmasi Hapus</h2>
                <p class="text-gray-600 dark:text-zink-400">Apakah anda yakin menghapus lokasi sarana pemuda ?</p>
                <div class="flex justify-center gap-4 mt-4">
                    <button id="confirmDelete" type="button" class="btn bg-red-500 text-white hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">Hapus</button>
                    <button id="cancelDelete" type="button" class="btn bg-gray-300 text-gray-700 hover:bg-gray-400 dark:bg-zink-600 dark:text-zink-100 dark:hover:bg-zink-700">Batal</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let deleteFormId = '';

        function openDeleteModal(id) {
            deleteFormId = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        document.getElementById('confirmDelete').addEventListener('click', function () {
            if (deleteFormId) {
                document.getElementById('delete-form-' + deleteFormId).submit();
            }
        });

        document.getElementById('cancelDelete').addEventListener('click', function () {
            document.getElementById('deleteModal').classList.add('hidden');
        });
    </script>
    @endpush
@endsection
