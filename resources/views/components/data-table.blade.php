@props([
    'url',
    'initialFilters' => []
])

<div class="space-y-6"
     x-data="dataTable('{{ $url }}', {{ Js::from($initialFilters) }})"
     x-init="
        fetchData();
        $watch('search', () => {
            page = 1;
            fetchData();
        });
        $watch('filters', () => {
            page = 1;
            fetchData();
        }, { deep: true });

        // Watch for changes in filters.jenis_surat to control filters.jenis_cuti
        $watch('filters.jenis_surat', (value) => {
            if (value !== 'cuti') {
                this.filters.jenis_cuti = 'all'; // Reset jenis_cuti if jenis_surat is not 'cuti'
            }
        });
     ">

    <!-- Page Header -->
    @isset($header)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            {{ $header }}
        </div>
    @endisset

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Filters Section (Optional) -->
        @isset($filters)
            <div class="p-4 md:p-6 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center gap-2 mb-3">
                    <x-lucide-filter class="w-4 h-4 text-gray-600" />
                    <h3 class="text-sm font-semibold text-gray-700">Filter Data</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{ $filters }}
                </div>
            </div>
        @endisset

        <!-- Search and Actions -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-2">
                    <select x-model="limit" @change="page = 1; fetchData()"
                            class="block w-24 pl-3 pr-8 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <p class="text-sm text-gray-600">data per halaman</p>
                </div>
                <div class="relative flex-1 md:flex-initial">
                    <x-lucide-search class="w-5 h-5 text-gray-400 absolute top-1/2 left-3 -translate-y-1/2" />
                    <input type="text" x-model.debounce.500ms="search" placeholder="Cari..."
                           class="block w-full md:w-80 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto relative">
            <!-- Loading Overlay -->
            <div x-show="isLoading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
                <div class="flex flex-col items-center gap-3">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
                    <p class="text-sm text-gray-600">Memuat data...</p>
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        {{ $thead }}
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-if="!isLoading && items.length === 0">
                        <tr>
                            <td colspan="100%" class="px-6 py-12">
                                <div class="text-center">
                                    <x-lucide-inbox class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                                    <p class="text-sm font-medium text-gray-900">Tidak ada data ditemukan</p>
                                    <p class="text-sm text-gray-500 mt-1">Coba ubah filter atau kata kunci pencarian Anda</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-for="(item, index) in items" :key="item.id || index">
                        {{ $tbody }}
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 md:p-6 border-t border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span x-text="startRecord" class="font-medium"></span>
                        sampai
                        <span x-text="endRecord" class="font-medium"></span>
                        dari
                        <span x-text="total" class="font-medium"></span>
                        hasil
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="prevPage" :disabled="page === 1"
                            class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                        <x-lucide-chevron-left class="w-4 h-4" />
                        Sebelumnya
                    </button>

                    <!-- Page Numbers (optional) -->
                    <div class="hidden md:flex items-center gap-1">
                        <template x-for="pageNum in visiblePages" :key="pageNum">
                            <button @click="page = pageNum; fetchData()"
                                    :class="page === pageNum ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                                    class="px-3 py-2 text-sm font-medium border border-gray-300 rounded-md transition-colors duration-200"
                                    x-text="pageNum">
                            </button>
                        </template>
                    </div>

                    <button @click="nextPage" :disabled="page === totalPages"
                            class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                        Selanjutnya
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
    function dataTable(url, initialFilters) {
        return {
            // State
            items: [],
            total: 0,
            page: 1,
            limit: 10,
            search: '',
            sortColumn: 'id',
            sortDirection: 'asc',
            isLoading: true,
            url: url,
            filters: initialFilters || {},

            // Computed
            get totalPages() {
                return Math.ceil(this.total / this.limit);
            },
            get startRecord() {
                return this.total === 0 ? 0 : Math.min((this.page - 1) * this.limit + 1, this.total);
            },
            get endRecord() {
                return Math.min(this.page * this.limit, this.total);
            },
            get visiblePages() {
                const pages = [];
                const maxVisible = 5;
                let start = Math.max(1, this.page - Math.floor(maxVisible / 2));
                let end = Math.min(this.totalPages, start + maxVisible - 1);

                if (end - start < maxVisible - 1) {
                    start = Math.max(1, end - maxVisible + 1);
                }

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }
                return pages;
            },
            // New computed property for jenis_cuti disabled state
            get isJenisCutiDisabled() {
                return this.filters.jenis_surat !== 'cuti';
            },

            // Methods
            fetchData() {
                this.isLoading = true;
                const fetchUrl = new URL(this.url);
                fetchUrl.searchParams.set('page', this.page);
                fetchUrl.searchParams.set('limit', this.limit);
                fetchUrl.searchParams.set('search', this.search);
                fetchUrl.searchParams.set('sort', this.sortColumn);
                fetchUrl.searchParams.set('dir', this.sortDirection);
                fetchUrl.searchParams.set('offset', (this.page - 1) * this.limit);

                // Add dynamic filters to searchParams
                for (const key in this.filters) {
                    if (this.filters[key] !== null &&
                        this.filters[key] !== undefined &&
                        this.filters[key] !== '' &&
                        this.filters[key] !== 'all') { // Changed 'Semua' to 'all' to match current usage
                        fetchUrl.searchParams.set(key, this.filters[key]);
                    }
                }

                fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    this.items = data.data;
                    this.total = data.total;
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    this.$dispatch('open-notification-modal', {
                        type: 'danger',
                        title: 'Gagal Memuat Data',
                        message: 'Terjadi kesalahan saat mengambil data dari server.'
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            nextPage() {
                if (this.page < this.totalPages) {
                    this.page++;
                    this.fetchData();
                }
            },

            prevPage() {
                if (this.page > 1) {
                    this.page--;
                    this.fetchData();
                }
            },

            sortBy(column) {
                if (this.sortColumn === column) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortColumn = column;
                    this.sortDirection = 'asc';
                }
                this.page = 1;
                this.fetchData();
            },

            setFilter(key, value) {
                if (!this.filters) {
                    this.filters = {};
                }
                this.filters[key] = value;
            },

            clearFilters() {
                this.filters = {}; // Clear all filters
                this.search = '';
                this.page = 1;
                this.fetchData();
            },

            deleteItem(id) {
                const deleteUrl = `${this.url}/${id}`;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    this.fetchData();
                    this.$dispatch('open-notification-modal', {
                        type: 'success',
                        title: 'Sukses',
                        message: data.message || 'Data berhasil dihapus.'
                    });
                })
                .catch(error => {
                    console.error('There was a problem with the delete operation:', error);
                    this.$dispatch('open-notification-modal', {
                        type: 'danger',
                        title: 'Gagal Menghapus Data',
                        message: 'Terjadi kesalahan saat menghapus data dari server.'
                    });
                });
            }
        }
    }
</script>
@endPushOnce
