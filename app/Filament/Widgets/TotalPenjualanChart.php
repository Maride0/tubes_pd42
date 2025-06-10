<?php 
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;

class TotalPenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Total Penjualan';

    protected function getData(): array
    {
        $data = Penjualan::query()
            ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
            ->join('menu', 'penjualan_barang.kode_menu', '=', 'menu.id') // ✅ fix join-nya
            ->where('penjualan.status', 'bayar')
            ->selectRaw('menu.nama_menu, SUM(penjualan_barang.harga_jual * penjualan_barang.jumlah) as total_penjualan')
            ->groupBy('menu.nama_menu')
            ->get()
            ->map(function ($penjualan) {
                return [
                    'nama_menu' => $penjualan->nama_menu,
                    'total_penjualan' => $penjualan->total_penjualan,
                ];
            });

        if ($data->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B',
            '#8B5CF6', '#EC4899', '#22D3EE',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data->pluck('total_penjualan')->toArray(),
                    'backgroundColor' => $data->map(fn($item, $key) => $colors[$key % count($colors)])->toArray(),
                ],
            ],
            'labels' => $data->pluck('nama_menu')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
