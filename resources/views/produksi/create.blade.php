@extends('layoutsbootstrap.app')

@section('konten')
<div class="body-wrapper">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="card-title mb-4">Tambah Data Produksi</h4>

        <!-- Tampilkan Error -->
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('produksis.store') }}" method="POST">
          @csrf

          <!-- Kode Produksi -->
          <div class="mb-3">
            <label for="kode_produksi" class="form-label">Kode Produksi</label>
            <input type="text" class="form-control" id="kode_produksi" name="kode_produksi" 
                   value="{{ old('kode_produksi', $kode_produksi ?? 'PRD-' . strtoupper(uniqid())) }}" 
                   readonly>
          </div>

          <!-- Pilih Menu -->
          <div class="mb-3">
            <label for="menu_id" class="form-label">Pilih Menu</label>
            <select class="form-select" id="menu_id" name="menu_id" required>
              <option value="">-- Pilih Menu --</option>
              @foreach ($menus as $menu)
                <option value="{{ $menu->id }}" 
                  {{ old('menu_id') == $menu->id ? 'selected' : ($loop->first && !old('menu_id') ? 'selected' : '') }}>
                  {{ $menu->nama_menu }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Tanggal Produksi -->
          <div class="mb-3">
            <label for="tgl_produksi" class="form-label">Tanggal Produksi</label>
            <input type="date" class="form-control" id="tgl_produksi" name="tgl_produksi" 
                   value="{{ old('tgl_produksi', \Carbon\Carbon::today()->format('Y-m-d')) }}" required>
          </div>

          <!-- Jumlah Produksi -->
          <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah Produksi</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" 
                   value="{{ old('jumlah') }}" required>
          </div>

          <!-- Satuan -->
          <div class="mb-3">
            <label for="porsi" class="form-label">Satuan</label>
            <input type="text" class="form-control" id="porsi" name="porsi" value="1" readonly>
          </div>

          <!-- Keterangan -->
          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="keterangan" name="keterangan">{{ old('keterangan') }}</textarea>
          </div>

          <!-- Bahan Baku -->
          <div class="mb-3">
            <label class="form-label">Bahan Baku Digunakan</label>
            <div id="bahan_baku_repeater">
              <div class="mb-3 row repeater-item">
                <div class="col">
                  <select class="form-select" name="bahan_baku[0][id]" required>
                    <option value="">-- Pilih Bahan Baku --</option>
                    @foreach ($bahanBaku as $bahan)
                      <option value="{{ $bahan->id }}">{{ $bahan->nama_bahan_baku }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col">
                  <input type="number" class="form-control" name="bahan_baku[0][jumlah]" placeholder="Jumlah" required>
                </div>
              </div>
            </div>
            <button type="button" id="add-repeater" class="btn btn-sm btn-success mt-2">+ Tambah Bahan Baku</button>
          </div>

          <!-- Tombol -->
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('produksis.index') }}" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  let index = 1;

  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('add-repeater').addEventListener('click', function () {
      const repeater = document.getElementById('bahan_baku_repeater');
      const original = repeater.querySelector('.repeater-item');
      const clone = original.cloneNode(true);

      clone.querySelectorAll('select, input').forEach(el => {
        const field = el.name.includes('[id]') ? 'id' : 'jumlah';
        el.name = `bahan_baku[${index}][${field}]`;
        el.value = '';
      });

      index++;
      repeater.appendChild(clone);
    });
  });
</script>
@endsection
