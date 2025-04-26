<?php

namespace App\Filament\Resources\PilihDospem2SkripsiResource\Pages;

use App\Filament\Resources\PilihDospem2SkripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPilihDospem2Skripsis extends ListRecords
{
    protected static string $resource = PilihDospem2SkripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
