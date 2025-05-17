<?php
//bisa di definisi kaya gini
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Contoh1Controller;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route for the welcome page
Route::get('/', function () {
    //return view('welcome');
    return view('login');
});
// login customer
Route::get('/depan', [App\Http\Controllers\KeranjangController::class, 'daftarmenu'])
        ->middleware('customer')
        ->name('ubahpassword');
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
// login customer
Route::get('/depan', [App\Http\Controllers\KeranjangController::class, 'daftarmenu'])
     ->middleware('customer')
     ->name('depan');
// tambah keranjang
Route::post('/tambah', [App\Http\Controllers\KeranjangController::class, 'tambahKeranjang'])->middleware('customer');
Route::get('/lihatkeranjang', [App\Http\Controllers\KeranjangController::class, 'lihatkeranjang'])->middleware('customer');
Route::delete('/hapus/{menu_id}', [App\Http\Controllers\KeranjangController::class, 'hapus'])->middleware('customer');
Route::get('/lihatriwayat', [App\Http\Controllers\KeranjangController::class, 'lihatriwayat'])->middleware('customer');
// untuk autorefresh
Route::get('/cek_status_pembayaran_pg', [App\Http\Controllers\KeranjangController::class, 'cek_status_pembayaran_pg']);
Route::get('/login', function () {
    return view('login');
});

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// untuk ubah password
Route::get('/ubahpassword', [App\Http\Controllers\AuthController::class, 'ubahpassword'])
    ->middleware('customer')
    ->name('ubahpassword');
Route::post('/prosesubahpassword', [App\Http\Controllers\AuthController::class, 'prosesubahpassword'])
    ->middleware('customer')
;
// prosesubahpassword

// untuk contoh perusahaan
use App\Http\Controllers\PerusahaanController;
Route::resource('perusahaan', PerusahaanController::class);
Route::get('/perusahaan/destroy/{id}', [PerusahaanController::class,'destroy']);

// contoh sampel midtrans
use App\Http\Controllers\CobaMidtransController;
Route::get('/cekmidtrans', [CobaMidtransController::class, 'cekmidtrans']);

// proses pengiriman email
use App\Http\Controllers\PengirimanEmailController;
Route::get('/proses_kirim_email_pembayaran', [PengirimanEmailController::class, 'proses_kirim_email_pembayaran']);

Route::post('/form-produksi', function (Request $request) {
    // Generate kode produksi otomatis
    $kode_produksi = 'PRD-' . strtoupper(Str::random(8)); $validated = $request->validate([
        'kode_karyawan'           => 'required|exists:karyawans,id',
        'menu_id'                 => 'required|exists:menus,id',
        'tgl_produksi'            => 'required|date',
        'jumlah'                  => 'required|integer|min:1',
        'porsi'                   => 'required|integer|min:1',
        'keterangan'              => 'nullable|string',
        'bahan_baku'              => 'required|array|min:1',
        'bahan_baku.*.id'         => 'required|exists:bahan_bakus,id',
        'bahan_baku.*.nama'       => 'required|string',
        'bahan_baku.*.jumlah'     => 'required|numeric|min:0.01',
    ]);

    // Simpan ke database
    $produksi = Produksi::create([
        'kode_produksi' => $kode_produksi,
        'kode_karyawan' => $validated['kode_karyawan'],
        'kode_menu'     => $validated['menu_id'],
        'tgl_produksi'  => Carbon::parse($validated['tgl_produksi']),
        'jumlah'        => $validated['jumlah'],
        'porsi'         => $validated['porsi'],
        'keterangan'    => $validated['keterangan'] ?? null,
        'bahan_baku'    => json_encode($validated['bahan_baku']),
    ]);

    // Jika kamu pakai Filament, balasan bisa seperti ini:
    return response()->json([
        'success' => true,
        'message' => 'Data produksi berhasil disimpan!',
        'data' => $produksi,
    ]);
});