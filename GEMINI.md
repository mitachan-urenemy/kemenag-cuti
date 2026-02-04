# GEMINI Guidelines: Kemenag Bener Meriah

## Tech Stack

- **Backend:** Laravel 12, PHP
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Blade Icons
- **Auth:** Laravel Breeze
- **Tooling:** Vite, PHPUnit, Laravel Pint

## Commands

- **Setup:** `composer setup`
- **Dev Server:** `composer run dev`
- **Test:** `composer test`
- **Format Code:** `vendor/bin/pint`
- **Build:** `npm run build`

---

## Component Patterns

### 1. Data Table (`<x-data-table>`)

Server-side table. Controller must return specific JSON structure.

**Controller Logic:**

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
        $query = Models::query();
        // ... logika search, sort, paginate ...
        return response()->json([
            'data' => $pegawais->items(),
            'total' => $total,
        ]);
    }
    return view('route.index');
}
```

**2. View (`route/index.blade.php`)**

```html
<x-data-table url="{{ route('route.index') }}">
    <x-slot name="header"> // Tombol atau elemen lain di atas tabel </x-slot>

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

### 2. Forms (`<x-forms.*>`)

Location: `resources/views/components/forms`. Handles labels, errors, and notes automatically.
**Pattern:** Use `old('field', $model->field)` for state retention.

**Inputs:**

- `text`, `textarea`, `date`, `checkbox`
- **Select:** `:options="['id' => 'Label']" :selected="old(...)"`

```html
<x-forms.text name="nama" :value="old('nama', $item->nama)" required />
```

### 3. Feedback Components

**Toast (`<x-toast-notification>`)**

- **Backend:** `->with('notification', ['type'=>'success', 'title'=>'Title', 'message'=>'Msg'])`
- **Frontend:** `$dispatch('open-toast', { type: 'info', title: '...', message: '...' })`

**Confirm Modal (`<x-modal-confirm>`)**
Trigger via Alpine event for destructive actions.

```html
<button
    @click="$dispatch('open-confirm-modal', {
    title: 'Delete?',
    message: 'Are you sure?',
    action: '/route/id',
    method: 'DELETE'
})"
>
    Delete
</button>
```

### 4. Layout Components

**`<x-card>`**
Navigation card. Props: `icon`, `title`, `description`, `href`.

**`<x-content-card>`**
Main container for tables/forms.
Slots: `action` (header right), `footer` (bottom actions).

**[CRITICAL] Form Rule:**
When using `<x-content-card>` for forms, the `<form>` tag **MUST wrap** the component, not be inside it.
✅ **Correct:**

```html
<form method="POST">
    @csrf
    <x-content-card title="Edit Data">
        <!-- Inputs -->
        <x-slot name="footer"><button>Save</button></x-slot>
    </x-content-card>
</form>
```

❌ **Incorrect:** `<x-content-card><form>...</form></x-content-card>` (Buttons in footer won't submit).
