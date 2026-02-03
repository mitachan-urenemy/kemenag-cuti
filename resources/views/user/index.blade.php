<x-app-layout>
    <div class="mx-auto">
        <x-content-card
            icon="users"
            title="Manajemen User"
            subtitle="Kelola semua akun user yang terdaftar di sistem."
            :padding="false"
        >
            <x-slot name="action">
                <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg border border-transparent transition-colors duration-200 text-xs uppercase">
                    <x-lucide-user-plus class="w-4 h-4" />
                    Tambah User
                </a>
            </x-slot>

            <x-data-table
                url="{{ route('users.index') }}"
            >
                <x-slot name="thead">
                    <th scope="col" class="py-3.5 px-6 text-left cursor-pointer select-none" @click="sortBy('username')">
                        <div class="flex items-center gap-2">
                            Username
                            <x-lucide-chevrons-up-down class="w-4 h-4" />
                        </div>
                    </th>
                    <th scope="col" class="py-3.5 px-6 text-left cursor-pointer select-none" @click="sortBy('email')">
                        <div class="flex items-center gap-2">
                            Email
                            <x-lucide-chevrons-up-down class="w-4 h-4" />
                        </div>
                    </th>
                    <th scope="col" class="py-3.5 px-6 text-left cursor-pointer select-none" @click="sortBy('created_at')">
                        <div class="flex items-center gap-2">
                            Tanggal Buat
                            <x-lucide-chevrons-up-down class="w-4 h-4" />
                        </div>
                    </th>
                    <th scope="col" class="py-3.5 px-6 relative text-right">
                        <span class="sr-only">Aksi</span>
                    </th>
                </x-slot>

                <x-slot name="tbody">
                    <tr>
                        <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap" x-text="item.username"></td>
                        <td class="py-4 px-6 text-gray-600" x-text="item.email"></td>
                        <td class="py-4 px-6 text-gray-600" x-text="new Date(item.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })"></td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a :href="`{{ route('users.index') }}/${item.id}/edit`" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                <button
                                    @click="
                                        $dispatch('open-confirm-modal', {
                                            title: 'Konfirmasi Penghapusan',
                                            message: `Yakin ingin menghapus user <strong>${item.username}</strong>? Tindakan ini tidak dapat dibatalkan.`,
                                            action: `{{ route('users.index') }}/${item.id}`,
                                            method: 'DELETE'
                                        })
                                    "
                                    class="font-medium text-red-600 hover:text-red-800"
                                >
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
