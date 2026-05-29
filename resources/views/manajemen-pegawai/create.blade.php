<x-app-layout>
    <div class="mx-auto">
        <form id="pegawaiForm" method="POST" action="{{ route('manajemen-pegawai.store') }}"
              x-data="{
                  isAtasanSelected: 0,
                  hasExistingAtasan: @js($currentAtasan ? true : false),
                  atasanName: @js($currentAtasan->nama_lengkap ?? ''),
                  handleSubmit(e) {
                      if (this.isAtasanSelected == 1 && this.hasExistingAtasan) {
                          e.preventDefault();
                          $dispatch('open-confirm-modal', {
                              title: 'Ubah Kepala Pimpinan / Atasan?',
                              message: `Peringatan: Kantor ini sudah memiliki kepala pimpinan / atasan (<span class=\'font-semibold text-gray-900\'>${this.atasanName}</span>). Jika Anda melanjutkan, peran kepala unit lama akan otomatis diubah menjadi bukan atasan.`,
                              type: 'warning',
                              confirmText: 'Ya, Lanjutkan',
                              formId: 'pegawaiForm'
                          });
                          return false;
                      }
                  }
              }"
              @submit="handleSubmit($event)">
            @csrf
            <x-content-card
                title="Tambah Pegawai Baru"
                subtitle="Isi detail di bawah ini untuk menambahkan data pegawai dan membuat akun baru."
            >
                <x-slot name="action">
                    <a href="{{ route('manajemen-pegawai.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>
                </x-slot>

                <div class="space-y-6">
                    <!-- Section: Akun Pengguna -->
                    <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-key class="w-4 h-4 text-gray-500" />
                            Kredensial Login
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <x-forms.text
                                title="Username"
                                name="username"
                                placeholder="Masukkan Username Login"
                                :value="old('username')"
                                required
                            />

                            <x-forms.password
                                title="Password"
                                name="password"
                                placeholder="Masukkan Password Login"
                                required
                            />
                        </div>
                    </div>

                    <!-- Section: Informasi Pribadi -->
                    <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-user class="w-4 h-4 text-gray-500" />
                            Informasi Pribadi
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <x-forms.text
                                title="Nama Lengkap"
                                name="nama_lengkap"
                                placeholder="Masukkan Nama Lengkap"
                                :value="old('nama_lengkap')"
                                required
                            />

                            <x-forms.text
                                title="Nomor Induk Pegawai (NIP)"
                                name="nip"
                                placeholder="Masukkan Nomor Induk Pegawai"
                                :value="old('nip')"
                                required
                            />

                            <x-forms.select
                                title="Jenis Kelamin"
                                name="jenis_kelamin"
                                :options="['laki' => 'Laki-laki', 'perempuan' => 'Perempuan']"
                                :selected="old('jenis_kelamin')"
                                required
                            />

                            <x-forms.text
                                title="Tempat Lahir"
                                name="tempat_lahir"
                                placeholder="Masukkan Tempat Lahir"
                                :value="old('tempat_lahir')"
                            />

                            <div class="col-span-1">
                                <x-forms.date
                                    title="Tanggal Lahir"
                                    name="tanggal_lahir"
                                    :value="old('tanggal_lahir')"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Section: Kepegawaian -->
                    <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-briefcase class="w-4 h-4 text-gray-500" />
                            Detail Kepegawaian
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <x-forms.select
                                title="Status Kepegawaian"
                                name="status_kepegawaian"
                                :options="['PNS' => 'PNS', 'PPPK' => 'PPPK']"
                                :selected="old('status_kepegawaian')"
                                required
                            />

                            <x-forms.text
                                title="Pangkat / Golongan"
                                name="pangkat_golongan"
                                placeholder="Masukkan Pangkat/Golongan"
                                :value="old('pangkat_golongan')"
                            />

                            <x-forms.text
                                title="Jabatan"
                                name="jabatan"
                                placeholder="Masukkan Jabatan"
                                :value="old('jabatan')"
                            />

                            <x-forms.text
                                title="Unit Kerja"
                                name="unit_kerja"
                                placeholder="Masukkan Unit Kerja"
                                :value="old('unit_kerja')"
                            />

                            <x-forms.text
                                title="Pendidikan Terakhir"
                                name="pendidikan"
                                placeholder="Masukkan Pendidikan Terakhir"
                                :value="old('pendidikan')"
                            />

                            @if(auth()->user()->role === 'admin')
                                <x-forms.select
                                    title="Peran Atasan"
                                    name="is_atasan"
                                    :options="[0 => 'Bukan Atasan / Kepala Pimpinan', 1 => 'Atasan / Kepala Pimpinan']"
                                    :selected="old('is_atasan', 0)"
                                    x-model="isAtasanSelected"
                                    required
                                />
                            @else
                                <input type="hidden" name="is_atasan" value="0">
                            @endif
                        </div>
                    </div>

                    <!-- Section: Informasi Kontak -->
                    <div class="bg-gray-50/50 p-4 rounded-lg border border-gray-100">
                        <h3 class="text-sm font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <x-lucide-mail class="w-4 h-4 text-gray-500" />
                            Informasi Kontak
                        </h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <x-forms.text
                                title="Nomor HP"
                                name="nomor_hp"
                                placeholder="Masukkan Nomor HP Aktif"
                                :value="old('nomor_hp')"
                            />

                            <x-forms.text
                                title="Email"
                                name="email"
                                type="email"
                                placeholder="Masukkan Alamat Email"
                                :value="old('email')"
                            />
                        </div>
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('manajemen-pegawai.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                        <x-primary-button>{{ __('Simpan Pegawai') }}</x-primary-button>
                    </div>
                </x-slot>
            </x-content-card>
        </form>
    </div>

</x-app-layout>
