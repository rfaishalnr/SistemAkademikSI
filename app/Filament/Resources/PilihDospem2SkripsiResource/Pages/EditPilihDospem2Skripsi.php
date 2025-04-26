<?php

namespace App\Filament\Resources\PilihDospem2SkripsiResource\Pages;

use App\Filament\Resources\PilihDospem2SkripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPilihDospem2Skripsi extends EditRecord
{
    protected static string $resource = PilihDospem2SkripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
