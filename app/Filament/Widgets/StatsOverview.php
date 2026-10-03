<?php

namespace App\Filament\Widgets;

use App\Models\Alumni;
use App\Models\TracerResponse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalAlumni = Alumni::count();
        $totalResponses = TracerResponse::count();
        $bekerja = TracerResponse::where('status_utama', 'Bekerja')->count();
        $kuliah = TracerResponse::where('status_utama', 'Kuliah')->count();
        $wirausaha = TracerResponse::where('status_utama', 'Wirausaha')->count();
        $mencariKerja = TracerResponse::where('status_utama', 'Mencari Kerja')->count();

        $bekerjaPct = $totalResponses > 0 ? round(($bekerja / $totalResponses) * 100, 1) : 0;
        $kuliahPct = $totalResponses > 0 ? round(($kuliah / $totalResponses) * 100, 1) : 0;
        $wirausahaPct = $totalResponses > 0 ? round(($wirausaha / $totalResponses) * 100, 1) : 0;

        return [
            Stat::make('Total Alumni Terdata', $totalAlumni)
                ->description('Jumlah alumni tercatat di sistem')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Alumni Bekerja', "{$bekerja} ({$bekerjaPct}%)")
                ->description('Indikator BMW: Bekerja')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success'),

            Stat::make('Alumni Kuliah', "{$kuliah} ({$kuliahPct}%)")
                ->description('Indikator BMW: Melanjutkan Studi')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),

            Stat::make('Alumni Wirausaha', "{$wirausaha} ({$wirausahaPct}%)")
                ->description('Indikator BMW: Wirausaha')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning'),
        ];
    }
}
