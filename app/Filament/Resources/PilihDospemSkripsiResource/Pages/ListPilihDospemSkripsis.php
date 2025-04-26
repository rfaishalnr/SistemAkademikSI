<?php

namespace App\Filament\Resources\PilihDospemSkripsiResource\Pages;

use App\Filament\Resources\PilihDospemSkripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPilihDospemSkripsis extends ListRecords
{
    protected static string $resource = PilihDospemSkripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
