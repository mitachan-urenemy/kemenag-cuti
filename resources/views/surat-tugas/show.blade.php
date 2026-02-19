<x-app-layout>
    <div class="mx-auto">
        <x-content-card
            title="Detail Surat Tugas"
            subtitle="Surat tugas telah berhasil dibuat. Anda dapat mencetaknya atau mengunduh sebagai PDF."
            class="no-print"
        >
            <x-slot name="action">
                <div class="flex items-center gap-2">
                    @if(request('from') === 'riwayat')
                        <a href="{{ route('riwayat-surat') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    @else
                        <a href="{{ route('surat-tugas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    @endif

                    <button onclick="printSurat()" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-gray-600 border border-transparent rounded-lg hover:bg-gray-700">
                        <x-lucide-printer class="w-4 h-4" />
                        Cetak
                    </button>
                    <iframe id="print-frame" src="" style="position: absolute; width: 0; height: 0; border: none; overflow: hidden;"></iframe>
                    <script>
                        function printSurat() {
                            const frame = document.getElementById('print-frame');
                            frame.src = "{{ route('surat-tugas.show', ['surat_tugas' => $surat->id, 'print' => true]) }}";
                        }
                    </script>
                </div>
            </x-slot>

            {{-- Letter Preview --}}
            <div class="mt-6 flex justify-center bg-gray-100/50 p-8 rounded-xl border border-gray-200 overflow-auto">
                 {{-- Wrapper to ensure the paper centers correctly and shadows are visible --}}
                 <div class="transform scale-[0.8] md:scale-100 origin-top">
                     @include($template, $data)
                 </div>
            </div>
        </x-content-card>
    </div>
</x-app-layout>
