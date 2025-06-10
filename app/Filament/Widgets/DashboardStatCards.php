<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

// tambahan
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\penjualan;
use App\Models\Coa;
use App\Models\member;

use Illuminate\Support\Number;

class DashboardStatCards extends BaseWidget
{
        protected function getStats(): array
        {
            $startDate = ! is_null($this->filters['startDate'] ?? null) ?
                Carbon::parse($this->filters['startDate']) :
                null;

            $endDate = ! is_null($this->filters['endDate'] ?? null) ?
                Carbon::parse($this->filters['endDate']) :
                now();

            $isBusinessmembersOnly = $this->filters['businessmembersOnly'] ?? null;
            $businessmemberMultiplier = match (true) {
                boolval($isBusinessmembersOnly) => 2 / 3,
                blank($isBusinessmembersOnly) => 1,
                default => 1 / 3,
            };

            $diffInDays = $startDate ? $startDate->diffInDays($endDate) : 0;

            $revenue = (int) (($startDate ? ($diffInDays * 137) : 192100) * $businessmemberMultiplier);
            $newmembers = (int) (($startDate ? ($diffInDays * 7) : 1340) * $businessmemberMultiplier);
            $newOrders = (int) (($startDate ? ($diffInDays * 13) : 3543) * $businessmemberMultiplier);

            $formatNumber = function (int $number): string {
                if ($number < 1000) {
                    return (string) Number::format($number, 0);
                }

                if ($number < 1000000) {
                    return Number::format($number / 1000, 2) . 'k';
                }

                return Number::format($number / 1000000, 2) . 'm';
            };

            return [
        Stat::make('Total member', member::count())
            ->description('Jumlah member terdaftar'),

    Stat::make('Total Transaksi yang sudah dibayar', penjualan::where('status', 'bayar')->count())
        ->description('Jumlah transaksi yang sudah dibayar'),

        Stat::make('Total Transaksi yang belum dibayar', penjualan::where('status', 'pesan')->count())
        ->description('Jumlah transaksi yang belum dibayar'),


        Stat::make('Total Penjualan', rupiah(
            penjualan::where('status', 'bayar')->sum('tagihan')
        ))
            ->description('Jumlah nilai transaksi'),

        Stat::make('Total Keuntungan', rupiah(
            penjualan::query()
                ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id') 
                ->where('status', 'bayar')
                ->selectRaw('SUM((penjualan_barang.harga_jual - penjualan_barang.harga_beli) * penjualan_barang.jumlah) as total_penjualan')
                ->value('total_penjualan')
        ))
            ->description('Jumlah keuntungan'),


];
    }

    
}