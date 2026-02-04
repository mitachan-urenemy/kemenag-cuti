# GEMINI Project Guidelines: Kemenag Cuti

## Project Context

Laravel 12 web application for managing official correspondence (Surats, Letterheads, and Pegawais).

- **Backend:** Laravel 12, PHP
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Blade Icons
- **Auth:** Laravel Breeze
- **Tools:** Vite (Build), PHPUnit (Test), Laravel Pint (Style)

---

## Development Commands

### Initial Setup

Run once to install dependencies, configure `.env`, generate key, migrate DB, and build assets.

```bash
composer setup
```

````

### Local Development

Starts PHP server, Vite, Queue worker, and Pail logs concurrently.

```bash
composer run dev
```

### Code Quality & Testing

- **Formatting:** Always run `vendor/bin/pint` before committing.
- **Testing:** Use `composer test` to run PHPUnit suites.

### Production Build

```bash
npm run build
```

---

## Component Specifications

### 1. Interactive Data Table (`<x-data-table>`)

Server-side table component. Controller must handle JSON requests.

**Controller Implementation (Strict):**
The method _must_ check for JSON expectation and return this exact structure:

```php
public function index(Request $request)
{
    if ($request->wantsJson()) {
        $query = Pegawai::query();
        // Implement search & sort logic here

        $data = $query->paginate($request->per_page ?? 10);

        return response()->json([
            'data' => $data->items(), // Array of objects
            'total' => $data->total(), // Total records count
        ]);
    }
    return view('pegawai.index');
}
```

**View Usage:**

```html
<x-data-table url="{{ route('pegawai.index') }}">
    <x-slot name="thead">
        <!-- @click must reference database column name -->
        <th @click="sortBy('nama_lengkap')">Nama Lengkap</th>
    </x-slot>
    <x-slot name="tbody">
        <tr>
            <!-- Access properties directly via x-text -->
            <td x-text="item.nama_lengkap"></td>
        </tr>
    </x-slot>
</x-data-table>
```

### 2. Form Components (`<x-forms.*>`)

Location: `resources/views/components/forms`.
All components handle label, input, error messages, and helper notes automatically.

**General Pattern:**
Always use `old('field_name', $model->field_name)` for the `value`/`selected` props to maintain validation state.

**Available Inputs:**

**Text Input**

```html
<x-forms.text
    title="Label Display"
    name="input_name"
    :value="old('input_name', $model->field)"
    type="text|email|number"
    required
    note="Optional helper text"
/>
```

**Textarea**

```html
<x-forms.textarea
    title="Alamat"
    name="alamat"
    :value="old('alamat', $model->alamat)"
    rows="4"
/>
```

**Select Dropdown**

```html
<x-forms.select
    title="Jabatan"
    name="jabatan_id"
    :options="['1' => 'Staf', '2' => 'Kepala']"
    :selected="old('jabatan_id', $model->jabatan_id)"
    required
/>
```

**Date Picker**

```html
<x-forms.date
    title="Tanggal Lahir"
    name="tanggal_lahir"
    :value="old('tanggal_lahir', $model->tanggal_lahir?->format('Y-m-d'))"
/>
```

**Checkbox**

```html
<x-forms.checkbox
    title="Status Aktif"
    name="is_active"
    :checked="old('is_active', $model->is_active)"
    value="1"
/>
```

### 3. UI Feedback Components

**`<x-toast-notification>`**

Displays a small, non-blocking notification toast at the top-right of the screen.

**Usage:**

**A. From Backend (Controller)**
Use `with('notification', $data)` when redirecting. The toast will appear automatically.

```php
$notification = [
    'type' => 'success', // 'success', 'danger', 'warning', 'info'
    'title' => 'Berhasil!',
    'message' => 'Data berhasil disimpan.',
    'autoClose' => true // optional, default: true
];

return redirect()->route('some.route')->with('notification', $notification);
```

**B. From Frontend (Alpine.js)**
Dispatch the `open-toast` window event.

```html
<button @click="$dispatch('open-toast', {
    type: 'info',
    title: 'Informasi',
    message: 'Ini adalah pesan dari frontend.'
})">
    Show Toast
</button>
```

**`<x-modal-confirm>`**

Displays a blocking modal to confirm a destructive action (e.g., deleting data). The component handles the form submission, CSRF token, and HTTP method spoofing internally.

**Usage:**
This modal is triggered **exclusively from the frontend** by dispatching the `open-confirm-modal` event.

Simply create a button that dispatches the event with the required data.

```html
<button @click="$dispatch('open-confirm-modal', {
    title: 'Konfirmasi Penghapusan',
    message: 'Yakin ingin menghapus <strong>Item Ini</strong>?',
    action: '/items/1', // The form's destination URL
    method: 'DELETE'  // The desired HTTP method
})">
    Delete Item
</button>
```

**Event Properties:**
- `title` (string): The modal's title.
- `message` (string): The confirmation message. Can contain HTML.
- `action` (string): The URL for the form's `action` attribute.
- `method` (string): The HTTP method to use (e.g., 'POST', 'DELETE', 'PUT').

### 4. Navigation & Content Cards

**`<x-card>`**

A small, clickable card for dashboards and navigation menus. The entire card becomes a link if `href` is provided.

**Usage:**

```html
<x-card
    icon="users"
    title="Manajemen Pegawai"
    description="Kelola data, tambah, ubah, dan hapus pegawai."
    href="{{ route('pegawai.index') }}"
/>
```
- **Props:** `icon`, `title`, `description`, `href`.

---

**`<x-content-card>`**

A large container for main page content like data tables or forms. It provides a structured layout with a header, body, and footer.

**Usage with Forms:**

```html
<form method="post" action="...">
    @csrf
    <x-content-card
        icon="user-plus"
        title="Tambah Data Baru"
        subtitle="Isi semua kolom yang diperlukan."
    >
        <x-slot name="action">
            <a href="..." class="button-secondary">Kembali</a>
        </x-slot>

        <!-- Form fields go here -->
        <x-forms.text name="nama" ... />

        <x-slot name="footer">
            <div class="text-right">
                <x-primary-button>Simpan Data</x-primary-button>
            </div>
        </x-slot>
    </x-content-card>
</form>
```

> **[PERINGATAN] Aturan Penggunaan Form**
> Saat menggunakan `<x-content-card>` untuk membungkus sebuah form, tag `<form>` **HARUS** berada di luar (membungkus) `<x-content-card>`. Ini penting agar tombol submit yang diletakkan di dalam slot `footer` tetap berada di dalam lingkup `<form>` dan dapat berfungsi dengan benar.

- **Props:** `icon`, `title`, `subtitle`, `padding`.
- **Slots:** `action` (untuk area kanan header), `(default)` (untuk body), `footer`.
````
