<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

// tambahan untuk akses 
use App\Models\Penjualan;
use App\Models\PenjualanBarang;
use App\Models\Pembayaran;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

// untuk notifikasi
use Filament\Notifications\Notification;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    public string $activeStep = 'Pembayaran';

    //penanganan kalau status masih kosong 
    protected function beforeCreate(): void
    {
        $this->data['status'] = $this->data['status'] ?? 'pesan';

        if (!empty($this->data['id_member'])) {
            $member = Member::find($this->data['id_member']);
            if ($member) {
                $this->data['nama'] = $member->nama;
            }
        }
    }


    // tambahan untuk simpan
    protected function getFormActions(): array
    {
        return [];
    }

    // penanganan
    public function simpanPembayaran()
    {
        $data = $this->form->getState();
        $penjualan = Penjualan::where('no_penjualan', $data['no_penjualan'])->first();
        
        //buat update status ya nep, inget!
        if ($penjualan) {
        $penjualan->update(['status' => 'bayar']);

        // Simpan ke tabel pembayaran
        Pembayaran::create([
            'penjualan_id' => $penjualan->id,
            'tgl_bayar'    => now(),
            'jenis_pembayaran' => 'tunai',
            'transaction_time' => now(),
            'gross_amount'       => $penjualan->tagihan, // Sesuaikan dengan field di tabel pembayaran
            'order_id' => $penjualan->no_penjualan,
        ]);
        
        // Notifikasi sukses
        Notification::make()
            ->title('Pembayaran Berhasil!')
            ->success()
            ->send();
        }

    }
}