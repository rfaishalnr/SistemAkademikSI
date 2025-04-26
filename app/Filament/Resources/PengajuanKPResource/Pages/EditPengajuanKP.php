<?php

namespace App\Filament\Resources\PengajuanKPResource\Pages;

use App\Filament\Resources\PengajuanKPResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanKP extends EditRecord
{
    protected static string $resource = PengajuanKPResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
