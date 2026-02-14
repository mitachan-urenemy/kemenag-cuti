<x-app-layout>
    <div class="mx-auto">
        <x-content-card
            icon="users"
            title="Manajemen Pegawai"
            subtitle="Daftar semua data pegawai yang terdaftar di sistem."
            :padding="false"
        >
            <x-slot name="action">
                <a href="{{ route('pegawai.create') }}" class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all cursor-pointer flex items-center gap-2">
                    <x-lucide-user-plus class="w-4 h-4" />
                    Tambah Pegawai
                </a>
            </x-slot>

            <x-data-table url="{{ route('pegawai.index') }}">
                <x-slot name="thead">
                    <th scope="col" class="py-3.5 px-6 text-left cursor-pointer select-none" @click="sortBy('nama_lengkap')">
                        <div class="flex items-center gap-2">
                            Nama Lengkap
                            <x-lucide-chevrons-up-down class="w-4 h-4" />
                        </div>
                    </th>
                    <th scope="col" class="py-3.5 px-6 text-left cursor-pointer select-none" @click="sortBy('nip')">
                        <div class="flex items-center gap-2">
                            NIP
                            <x-lucide-chevrons-up-down class="w-4 h-4" />
                        </div>
                    </th>
                    <th scope="col" class="py-3.5 px-6 text-left cursor-pointer select-none" @click="sortBy('jabatan')">
                        <div class="flex items-center gap-2">
                            Jabatan
                            <x-lucide-chevrons-up-down class="w-4 h-4" />
                        </div>
                    </th>
                    <th scope="col" class="py-3.5 px-6 text-left cursor-pointer select-none" @click="sortBy('status_pegawai')">
                        <div class="flex items-center gap-2">
                            Status
                            <x-lucide-chevrons-up-down class="w-4 h-4" />
                        </div>
                    </th>
                    <th scope="col" class="py-3.5 px-6 relative">
                        <span class="sr-only">Aksi</span>
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr class="border-b border-gray-200/80 hover:bg-gray-50/50">
                        <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap" x-text="item.nama_lengkap"></td>
                        <td class="py-4 px-6 text-gray-600" x-text="item.nip || '-'"></td>
                        <td class="py-4 px-6 text-gray-600" x-text="item.jabatan"></td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span :class="{
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border': true,
                                'bg-green-50 text-green-700 border-green-100 ring-1 ring-inset ring-green-600/20': item.status_pegawai === 'PNS',
                                'bg-amber-50 text-amber-700 border-amber-100 ring-1 ring-inset ring-amber-600/20': item.status_pegawai === 'PPPK',
                            }" x-text="item.status_pegawai">
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <a :href="`/pegawai/${item.id}/edit`" class="font-medium text-indigo-600 hover:text-indigo-800">Edit</a>
                                <button type="button"
                                        @click="
                                            $dispatch('open-confirm-modal', {
                                                title: 'Konfirmasi Penghapusan',
                                                message: `Apakah Anda yakin ingin menghapus pegawai <strong>${item.nama_lengkap}</strong>? Tindakan ini tidak dapat dibatalkan.`,
                                                action: `/pegawai/${item.id}`,
                                                method: 'DELETE'
                                            })
                                        "
                                        class="font-medium text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </x-slot>
            </x-data-table>
        </x-content-card>
    </div>
</x-app-layout>
