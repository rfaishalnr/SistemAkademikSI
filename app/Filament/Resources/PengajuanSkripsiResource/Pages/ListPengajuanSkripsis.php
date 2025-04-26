<?php

namespace App\Filament\Resources\PengajuanSkripsiResource\Pages;

use App\Filament\Resources\PengajuanSkripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanSkripsis extends ListRecords
{
    protected static string $resource = PengajuanSkripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
