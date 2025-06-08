<!-- resources/views/produksi/form.blade.php -->

<form action="{{ route('produksi.store') }}" method="POST">
    @csrf

    <label>Menu</label>
    <select name="menu_id" required>
        @foreach($menus as $menu)
            <option value="{{ $menu->id }}">{{ $menu->nama }}</option>
        @endforeach
    </select>

    <label>Jumlah Produksi (Porsi)</label>
    <input type="number" name="jumlah" min="1" required>

    <label>Tanggal Produksi</label>
    <input type="datetime-local" name="tgl_produksi" required>

    <label>Keterangan (Opsional)</label>
    <input type="text" name="keterangan">

    <button type="submit">Simpan Produksi</button>
</form>
