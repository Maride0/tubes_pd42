<?php 
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penjualan;
use Carbon\Carbon;

class PenjualanPerBulanChart extends ChartWidget
{
    protected static ?string $heading = null;

    public function getHeading(): string
    {
        return 'Penjualan Per Bulan ' . date('Y');
    }

    protected function getData(): array
    {
        $year = now()->year;

        $orders = Penjualan::query()
            ->join('penjualan_barang', 'penjualan.id', '=', 'penjualan_barang.penjualan_id')
            ->join('menu', 'penjualan_barang.kode_menu', '=', 'menu.id')
            ->where('penjualan.status', 'bayar')
            ->whereYear('penjualan.tanggal', $year)
            ->selectRaw('MONTH(penjualan.tanggal) as month, SUM(penjualan_barang.harga_jual * penjualan_barang.jumlah) as total_penjualan')
            ->groupBy('month')
            ->pluck('total_penjualan', 'month');

        $allMonths = collect(range(1, 12));

        $data = $allMonths->map(fn($month) => $orders->get($month, 0));

        $labels = $allMonths->map(fn($month) =>
            Carbon::create()->month($month)->locale('id')->translatedFormat('F')
        );

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data,
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
