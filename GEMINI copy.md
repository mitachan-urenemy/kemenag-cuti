# GEMINI Project Context: Kemenag Cuti

## Project Overview

This is a web application built with the **Laravel 12** framework. Based on the database schema, its primary purpose is to manage official correspondence, including letterheads (`kop_surats`), employees (`pegawais`), and the letters (`surats`) themselves. The name "Kemenag Cuti" suggests it might be related to leave management (`cuti`) for the Ministry of Religious Affairs (`Kemenag`).

The application uses a standard Laravel stack:

- **Backend:** PHP / Laravel
- **Frontend:** Blade templates, Tailwind CSS, and Alpine.js
- **Database:** The schema includes tables for `users`, `pegawais`, `surats`, and `kop_surats`.
- **Authentication:** Uses Laravel Breeze for user authentication.
- **Build Tool:** Vite for frontend asset compilation.

## Building and Running

### 1. Initial Setup

To set up the project for the first time, run the built-in `setup` script from `composer.json`. This will install all dependencies, create a `.env` file, generate an application key, run migrations, and build frontend assets.

```bash
composer setup
```

### 2. Running the Development Environment

A `dev` script is configured in `composer.json` to run all necessary development servers concurrently. This includes the PHP server, the Vite server, the queue listener, and the log pail.

```bash
composer run dev
```

If you need to run services individually:

- **Start Laravel Server:**
    ```bash
    php artisan serve
    ```
- **Start Vite Dev Server:**
    ```bash
    npm run dev
    ```
- **Run Database Migrations:**
    ```bash
    php artisan migrate
    ```

### 3. Building for Production

To compile frontend assets for production, use the `build` script.

```bash
npm run build
```

## Development Conventions

### Testing

The project uses **PHPUnit** for testing. Tests are located in the `tests/` directory.

- **Run all tests:**
    ```bash
    composer test
    ```
    or
    ```bash
    php artisan test
    ```
    or
    ```bash
    vendor/bin/phpunit
    ```

### Code Style

The project uses **Laravel Pint** for code styling. To automatically format your code to match the project's style:

```bash
vendor/bin/pint
```

## Project Component Library

### `<x-data-table>`

Komponen Blade interaktif untuk menampilkan data dalam bentuk tabel dengan fitur server-side.

**Fitur:**
- Pencarian data (debounced)
- Sorting per kolom
- Paginasi
- Pemilihan jumlah data per halaman
- Loading & Empty state

**Contoh Penggunaan:**

**1. Controller (`PegawaiController.php`)**
Pastikan method `index` bisa merespon request JSON.
```php
public function index(Request $request)
{
    if ($request->wantsJson()) {
        $query = Pegawai::query();
        // ... logika search, sort, paginate ...
        return response()->json([
            'data' => $pegawais->items(),
            'total' => $total,
        ]);
    }
    return view('pegawai.index');
}
```

**2. View (`pegawai/index.blade.php`)**
```html
<x-data-table url="{{ route('pegawai.index') }}">
    <x-slot name="header">
        // Tombol atau elemen lain di atas tabel
    </x-slot>

    <x-slot name="thead">
        <th @click="sortBy('nama_lengkap')">Nama</th>
        // ... header lainnya
    </x-slot>

    <x-slot name="tbody">
        <tr>
            <td x-text="item.nama_lengkap"></td>
            // ... body lainnya
        </tr>
    </x-slot>
</x-data-table>
```

### Form Components (`<x-forms.*>`)

Kumpulan komponen "all-in-one" untuk membangun form, terletak di `resources/views/components/forms`. Setiap komponen sudah termasuk label, input, pesan error, dan catatan.

**`<x-forms.text>`**
```html
<x-forms.text
    title="Nama Pegawai"
    name="nama_lengkap"
    :value="old('nama_lengkap', $pegawai->nama_lengkap)"
    required
    placeholder="Masukkan nama lengkap"
/>
```
- **Props:** `title`, `name`, `type` (default: 'text'), `placeholder`, `note`, `required`, `value`.

**`<x-forms.textarea>`**
```html
<x-forms.textarea
    title="Alamat"
    name="alamat"
    :value="old('alamat', $pegawai->alamat)"
    rows="3"
/>
```
- **Props:** `title`, `name`, `placeholder`, `note`, `required`, `rows` (default: 4), `value`.

**`<x-forms.select>`**
```html
<x-forms.select
    title="Pilih Jabatan"
    name="jabatan_id"
    :options="['1' => 'Staf', '2' => 'Kepala Seksi']"
    :selected="old('jabatan_id', $pegawai->jabatan_id)"
    required
/>
```
- **Props:** `title`, `name`, `note`, `required`, `options` (array), `selected`.

**`<x-forms.date>`**
```html
<x-forms.date
    title="Tanggal Lahir"
    name="tanggal_lahir"
    :value="old('tanggal_lahir', $pegawai->tanggal_lahir?->format('Y-m-d'))"
    required
/>
```
- **Props:** `title`, `name`, `placeholder`, `note`, `required`, `value`.

**`<x-forms.checkbox>`**
```html
<x-forms.checkbox
    title="Status Aktif"
    name="is_active"
    :checked="old('is_active', $pegawai->is_active)"
/>
```
- **Props:** `title`, `name`, `note`, `required`, `value` (default: '1'), `checked` (boolean).