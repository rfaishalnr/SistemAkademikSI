<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Ketentuan;
use App\Models\User;
use Filament\Widgets\Widget;
use Filament\Support\Enums\IconPosition;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Admin', User::count()),
            Stat::make('Jumlah Dosen', Dosen::count()),
            Stat::make('Jumlah Mahasiswa', Mahasiswa::count()),
            Stat::make('Total Ketentuan', Ketentuan::count()),
        ];
    }

}
