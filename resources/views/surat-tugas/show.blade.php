<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-content-card
            icon="briefcase"
            title="Detail Surat Tugas"
            subtitle="Surat tugas telah berhasil dibuat. Anda dapat mencetaknya atau mengunduh sebagai PDF."
        >
            <x-slot name="action">
                <div class="flex items-center gap-2">
                    <a href="{{ route('surat-tugas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                    <a href="{{ route('surat-tugas.download', ['surat' => $surat]) }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700">
                        <x-lucide-download class="w-4 h-4" />
                        Unduh PDF
                    </a>
                    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-gray-600 border border-transparent rounded-lg hover:bg-gray-700">
                        <x-lucide-printer class="w-4 h-4" />
                        Cetak
                    </button>
                </div>
            </x-slot>

            {{-- Letter Preview --}}
            <div class="p-8 bg-white border border-gray-300 rounded-lg shadow-inner printable-area">
                @include($template, $data)
            </div>
        </x-content-card>
    </div>

    <style>
        @media print {
            /* Hide everything except printable area */
            body * {
                visibility: hidden;
            }

            .printable-area,
            .printable-area * {
                visibility: visible;
            }

            .printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0 !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            /* Remove background colors for printing */
            .printable-area {
                background: white !important;
            }
        }
    </style>
</x-app-layout>
