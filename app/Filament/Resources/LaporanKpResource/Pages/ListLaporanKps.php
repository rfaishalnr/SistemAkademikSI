<?php

namespace App\Filament\Resources\LaporanKpResource\Pages;

use App\Filament\Resources\LaporanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanKps extends ListRecords
{
    protected static string $resource = LaporanKpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
