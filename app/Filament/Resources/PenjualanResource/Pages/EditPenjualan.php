<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use App\Models\Pembayaran;
use App\Models\PenjualanBarang;
use App\Models\Menu;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPenjualan extends EditRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function simpanPembayaran()
{
    $penjualan = $this->record;

    if ($penjualan->status !== 'bayar') {
        $penjualan->update([
            'status' => 'bayar',
        ]);

        Pembayaran::create([
            'penjualan_id'     => $penjualan->id,
            'tgl_bayar'        => now(),
            'jenis_pembayaran' => 'tunai',
            'transaction_time' => now(),
            'gross_amount'     => $penjualan->tagihan,
            'order_id'         => $penjualan->no_penjualan,
        ]);

        Notification::make()
            ->title('Pembayaran berhasil!')
            ->success()
            ->send();
        }
    }
}