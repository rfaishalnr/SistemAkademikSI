<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanKpResource\Pages;
use App\Models\LaporanKp;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\TextColumn;


class LaporanKpResource extends Resource
{
    protected static ?string $model = LaporanKp::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Review Laporan KP';
    protected static ?string $navigationGroup = 'Kelola Pengajuan KP';

    public static function getNavigationGroup(): ?string
    {
        return 'Kelola Pengajuan KP';
    }
    
    public static function getNavigationSort(): int
    {
        return -1; // Nilai yang lebih besar dari -2 tapi masih negatif
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('file')
                ->label('File Laporan')
                ->disk('public')
                ->downloadable()
                ->disabled(), // Admin tidak bisa ubah file

            Forms\Components\TextInput::make('nilai')
                ->label('Nilai')
                ->numeric()
                ->minValue(0)
                ->maxValue(100),

            Forms\Components\Textarea::make('catatan')
                ->label('Catatan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.name')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),

                    Tables\Columns\TextColumn::make('file')
                    ->label('Nama File')
                    ->formatStateUsing(fn ($state) => basename($state))
                    ->tooltip(fn ($state) => $state)
                    ->limit(30)
                    ->url(fn ($record) => Storage::url($record->file_path), true)
                    ->openUrlInNewTab(),
                
                
                
                
                
                

                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since(),
            ])
            ->actions([
                Action::make('review')
                ->label('Review')
                ->form([
                    Forms\Components\Placeholder::make('file_info')
                        ->label('File')
                        ->content(function ($record) {
                            $url = Storage::url($record->file);
                            $filename = basename($record->file);
                            return new \Illuminate\Support\HtmlString(
                                "<a href=\"{$url}\" target=\"_blank\" class=\"text-blue-500 underline\">{$filename}</a>"
                            );
                        }),
            
                    Forms\Components\TextInput::make('nilai')
                        ->label('Nilai')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(fn ($record) => $record->nilai),
            
                    Forms\Components\Textarea::make('catatan')
                        ->label('Catatan (Opsional)')
                        ->nullable() // supaya boleh dikosongkan
                        ->default(fn ($record) => $record->catatan),
                ])
                ->action(function ($record, $data) {
                    $record->update([
                        'nilai' => $data['nilai'],
                        'catatan' => $data['catatan'],
                    ]);
                })
                ->modalSubmitActionLabel('Simpan Penilaian'),
            

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanKps::route('/'),
            'create' => Pages\CreateLaporanKp::route('/create'),
            'edit' => Pages\EditLaporanKp::route('/{record}/edit'),
        ];
    }
}
