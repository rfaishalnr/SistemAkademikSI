<?php

namespace App\Filament\Resources\PengajuanSkripsiResource\Pages;

use App\Filament\Resources\PengajuanSkripsiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanSkripsi extends EditRecord
{
    protected static string $resource = PengajuanSkripsiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
