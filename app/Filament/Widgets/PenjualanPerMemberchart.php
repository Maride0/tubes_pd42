<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PenjualanPerMemberChart extends ChartWidget
{
    protected static ?string $heading = 'Prosentase Penjualan per member';

    protected function getData(): array
    {
        $data = DB::table('penjualan_barang')
    ->join('penjualan', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
    ->join('member', 'member.id', '=', 'penjualan.id_member')
    ->select('penjualan.id_member', 'member.nama', DB::raw('SUM(penjualan_barang.harga_jual * penjualan_barang.jumlah) as total'))
    ->groupBy('penjualan.id_member', 'member.nama')
    ->get();


        $labels = $data->pluck('nama')->toArray();
        $values = $data->pluck('total')->toArray();

        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#00A36C', '#800000',
            '#808000', '#4682B4',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $values,
                    'backgroundColor' => array_slice($colors, 0, count($values)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}