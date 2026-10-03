<?php

namespace App\Filament\Widgets;

use App\Models\TracerResponse;
use Filament\Widgets\ChartWidget;

class BmwChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Distribusi BMW (Bekerja, Melanjutkan, Wirausaha)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $bekerja = TracerResponse::where('status_utama', 'Bekerja')->count();
        $kuliah = TracerResponse::where('status_utama', 'Kuliah')->count();
        $wirausaha = TracerResponse::where('status_utama', 'Wirausaha')->count();
        $mencariKerja = TracerResponse::where('status_utama', 'Mencari Kerja')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Alumni',
                    'data' => [$bekerja, $kuliah, $wirausaha, $mencariKerja],
                    'backgroundColor' => [
                        '#10B981', // Success green for Bekerja
                        '#3B82F6', // Info blue for Kuliah
                        '#F59E0B', // Warning amber for Wirausaha
                        '#EF4444', // Danger red for Mencari Kerja
                    ],
                ],
            ],
            'labels' => ['Bekerja', 'Melanjutkan (Kuliah)', 'Wirausaha', 'Mencari Kerja'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
