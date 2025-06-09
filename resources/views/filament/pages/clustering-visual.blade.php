<x-filament-panels::page>
    <h2 class="text-lg font-bold mb-4">Visualisasi Clustering</h2>

    <canvas id="clusterChart" width="400" height="200"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const data = @json($dataPoints);

        // Mengelompokkan berdasarkan cluster
        const grouped = {};
        data.forEach(point => {
            if (!grouped[point.cluster]) grouped[point.cluster] = [];
            grouped[point.cluster].push({ x: point.x, y: point.y, name: point.name });
        });

        // Warna sesuai request: pink, hijau, ungu, orange, biru tua
        const colors = [
            'rgba(255, 105, 180, 0.7)',  // pink
            'rgba(0, 128, 0, 0.7)',      // hijau
            'rgba(128, 0, 128, 0.7)',    // ungu
            'rgba(255, 165, 0, 0.7)',    // orange
            'rgba(0, 0, 139, 0.7)'       // biru tua (darkblue)
        ];

        const datasets = Object.keys(grouped).map((cluster, index) => ({
            label: cluster,
            data: grouped[cluster],
            backgroundColor: colors[index % colors.length],
        }));

        new Chart(document.getElementById('clusterChart'), {
            type: 'scatter',
            data: { datasets },
            options: {
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            title: function(tooltipItems) {
                                return tooltipItems[0].raw.name;
                            },
                            label: function(tooltipItem) {
                                return `X: ${tooltipItem.raw.x}, Y: ${tooltipItem.raw.y}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Total Belanja'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Transaksi'
                        }
                    }
                }
            }
        });
    </script>
</x-filament-panels::page>
