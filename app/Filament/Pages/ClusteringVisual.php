<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Phpml\Clustering\KMeans; 

class ClusteringVisual extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.clustering-visual';

    public function getViewData(): array
    {
        // Ambil data transaksi status 'bayar' beserta nama dan id member
        $rawData = DB::table('penjualan')
        ->join('member', 'penjualan.id_member', '=', 'member.id')
        ->where('penjualan.status', 'bayar')
        ->select(
            'member.id as member_id',
            'member.nama',
            DB::raw('SUM(penjualan.tagihan) as total_belanja'),
            DB::raw('COUNT(*) as jumlah_transaksi')
        )
        ->groupBy('member.id', 'member.nama')
        ->orderBy('member.nama')
        ->get();


        // Gabungkan data per member: total belanja & jumlah transaksi
        $members = [];
        foreach ($rawData as $row) {
            $members[] = [
                'nama' => $row->nama,
                'total_belanja' => (float) $row->total_belanja,
                'jumlah_transaksi' => (int) $row->jumlah_transaksi,
            ];
        }


       
        $samples = array_values($members);
        $coordinates = array_map(fn($m) => [
            $m['total_belanja'],
            $m['jumlah_transaksi'],
        ], $samples);

        // Inisialisasi KMeans dengan 5 cluster 
        $kmeans = new KMeans(5);
        $clusters = $kmeans->cluster($coordinates);

        // Gabungkan hasil cluster dengan data asli
        $dataPoints = [];

        foreach ($clusters as $clusterIndex => $cluster) {
            foreach ($cluster as $point) {
               
                foreach ($samples as $key => $sample) {
                    if (
                        $sample['total_belanja'] === $point[0] &&
                        $sample['jumlah_transaksi'] === $point[1]
                    ) {
                        $dataPoints[] = [
                            'x' => $sample['total_belanja'],
                            'y' => $sample['jumlah_transaksi'],
                            'name' => $sample['nama'],
                            'cluster' => 'Cluster ' . ($clusterIndex + 1),
                        ];
                        unset($samples[$key]);
                        break;
                    }
                }
            }
        }

        return [
            'dataPoints' => $dataPoints,
        ];
    }
}
