<x-app-layout>
    <x-data-table :url="route('admin.keluarga.index')">
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <x-lucide-home class="w-7 h-7 text-indigo-600" />
                        Manajemen Data Keluarga
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Kelola data keluarga, anggota, laporan, dan tingkat dampak.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.keluarga.create_for_keluarga') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-sm transition-colors duration-200">
                        <x-lucide-user-plus class="w-5 h-5" />
                        Jadikan User
                    </a>
                    <a href="{{ route('admin.keluarga.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition-colors duration-200">
                        <x-lucide-plus class="w-5 h-5" />
                        Tambah Keluarga
                    </a>
                </div>
            </div>
        </x-slot>

        {{-- Optional Filters Slot --}}
        <x-slot name="filters">
            <div>
                <x-input-label for="tingkatdampakFilter" value="Tingkat Dampak" class="mb-2" />
                <x-select-input id="tingkatdampakFilter" x-model="filters.tingkatdampak" class="w-full">
                    <option value="Semua">Semua Tingkat Dampak</option>
                    @foreach($masterDampaks as $dampak)
                        <option value="{{ $dampak->id }}">
                            {{ $dampak->nama_dampak }}
                        </option>
                    @endforeach
                </x-select-input>
            </div>

            <div>
                <x-input-label for="statusFilter" value="Status Laporan" class="mb-2" />
                <x-select-input id="statusFilter" x-model="filters.status" class="w-full">
                    <option value="Semua">Semua Status</option>
                    <option value="Draft">Draft</option>
                    <option value="Valid">Valid</option>
                    <option value="Pending">Pending</option>
                    <option value="Ditolak">Ditolak</option>
                    <option value="Perbaikan">Perbaikan</option>
                </x-select-input>
            </div>

            {{-- Clear Filters Button --}}
            <div class="flex items-end">
                <button @click="clearFilters()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                    <x-lucide-x class="w-4 h-4" />
                    Reset Filter
                </button>
            </div>
        </x-slot>

        <x-slot name="thead">
            <th scope="col" @click="sortBy('id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                <div class="flex items-center gap-1">
                    ID
                    <span x-show="sortColumn === 'id'" class="ml-1">
                        <x-lucide-chevron-up x-show="sortDirection === 'asc'" class="w-4 h-4" />
                        <x-lucide-chevron-down x-show="sortDirection === 'desc'" class="w-4 h-4" />
                    </span>
                </div>
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Informasi Keluarga
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Alamat
            </th>
            <th scope="col" @click="sortBy('status_laporan')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                 <div class="flex items-center gap-1">
                    Status Laporan
                    <span x-show="sortColumn === 'status_laporan'" class="ml-1">
                        <x-lucide-chevron-up x-show="sortDirection === 'asc'" class="w-4 h-4" />
                        <x-lucide-chevron-down x-show="sortDirection === 'desc'" class="w-4 h-4" />
                    </span>
                </div>
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tingkat Dampak
            </th>
            <th scope="col" class="relative px-6 py-3 text-right">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</span>
            </th>
        </x-slot>

        <x-slot name="tbody">
            <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-semibold text-indigo-600" x-text="'#' + item.id"></span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-900" x-text="item.kepala_keluarga_nama"></span>
                        <span class="text-sm text-gray-500" x-text="'NIK: ' + (item.kepala_keluarga_nik ? item.kepala_keluarga_nik : 'N/A')"></span>
                        <span class="text-sm text-gray-500" x-text="'No. KK: ' + item.no_kk"></span>
                    </div>
                </td>
                 <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm text-gray-600" x-text="item.alamat_lengkap"></span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                              'bg-blue-100 text-blue-800': item.status_laporan === 'Draft',
                              'bg-yellow-100 text-yellow-800': item.status_laporan === 'Pending',
                              'bg-green-100 text-green-800': item.status_laporan === 'Valid',
                              'bg-red-100 text-red-800': item.status_laporan === 'Ditolak',
                          }"
                          x-text="item.status_laporan">
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                              'bg-gray-100 text-gray-800': item.master_dampak_warna === 'gray',
                              'bg-yellow-100 text-yellow-800': item.master_dampak_warna === 'yellow',
                              'bg-orange-100 text-orange-800': item.master_dampak_warna === 'orange',
                              'bg-red-100 text-red-800': item.master_dampak_warna === 'red',
                          }"
                          x-text="item.master_dampak_nama">
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                         <!-- Detail Button -->
                        <a :href="`{{ url('admin/keluarga') }}/${item.id}`"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition-colors duration-200"
                           title="Detail">
                            <x-lucide-eye class="w-4 h-4" />
                            <span class="hidden sm:inline">Detail</span>
                        </a>
                        <!-- Edit Button -->
                        <a :href="`{{ url('admin/keluarga') }}/${item.id}/edit`"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 text-xs font-medium rounded-lg transition-colors duration-200"
                           title="Edit">
                            <x-lucide-pencil class="w-4 h-4" />
                            <span class="hidden sm:inline">Edit</span>
                        </a>

                        <!-- Delete Button -->
                        <button type="button"
                                @click="window.dispatchEvent(new CustomEvent('open-confirm-modal', {
                                    detail: {
                                        title: 'Konfirmasi Penghapusan',
                                        message: `Apakah Anda yakin ingin menghapus data keluarga <strong>${item.kepala_keluarga_nama}</strong> (No. KK: ${item.no_kk})?`,
                                        action: `{{ url('admin/keluarga') }}/${item.id}`,
                                        method: 'DELETE'
                                    }
                                }))"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg transition-colors duration-200"
                                title="Hapus">
                            <x-lucide-trash-2 class="w-4 h-4" />
                            <span class="hidden sm:inline">Hapus</span>
                        </button>
                    </div>
                </td>
            </tr>
        </x-slot>
    </x-data-table>
</x-app-layout>
