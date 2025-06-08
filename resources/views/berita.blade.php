@extends('layout')

@section('konten')
<div class="container my-5">
    <div class="card shadow-sm p-4">
        <h2 class="mb-4 text-primary">
            🧾 Jumlah Resep Ditemukan: {{ count($hasil) }}
        </h2>

        @foreach ($hasil as $index => $data)
            <div class="card mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        @if ($data['gambar'])
                            <img src="{{ $data['gambar'] }}" alt="gambar resep" class="img-fluid rounded-start">
                        @else
                            <div class="text-muted p-3 text-center">Gambar tidak tersedia</div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $data['judul'] }}</h5>
                            <p class="card-text">
                                <strong>Sumber:</strong>
                                <a href="{{ $data['url'] }}" target="_blank" class="text-decoration-none text-primary">
                                    {{ $data['url'] }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>
@endsection