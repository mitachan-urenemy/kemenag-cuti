<x-app-layout>
    <div class="mx-auto">
        <x-content-card
            icon="users"
            title="Manajemen Pegawai"
            subtitle="Daftar semua data pegawai yang terdaftar di sistem."
            :padding="false"
        >
            <x-slot name="action">
                <a href="{{ route('pegawai.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <x-lucide-plus class="w-4 h-4" />
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
                    <th scope="col" class="py-3.5 px-6 relative">
                        <span class="sr-only">Aksi</span>
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr class="border-b border-gray-200/80 hover:bg-gray-50/50">
                        <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap" x-text="item.nama_lengkap"></td>
                        <td class="py-4 px-6 text-gray-600" x-text="item.nip || '-'"></td>
                        <td class="py-4 px-6 text-gray-600" x-text="item.jabatan"></td>
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
