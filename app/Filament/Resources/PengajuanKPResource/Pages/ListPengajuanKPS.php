<?php

namespace App\Filament\Resources\PengajuanKPResource\Pages;

use App\Filament\Resources\PengajuanKPResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanKPS extends ListRecords
{
    protected static string $resource = PengajuanKPResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
