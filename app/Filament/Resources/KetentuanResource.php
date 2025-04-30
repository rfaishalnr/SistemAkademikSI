<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KetentuanResource\Pages;
use App\Filament\Resources\KetentuanResource\RelationManagers;
use App\Models\Ketentuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KetentuanResource extends Resource
{
    protected static ?string $model = Ketentuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Ketentuan';
    protected static ?string $slug = 'kelola-Ketentuan';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('jenis')
                    ->label('Jenis')
                    ->options([
                        'KP' => 'Kerja Praktek (KP)',
                        'Skripsi' => 'Skripsi',
                    ])
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),

                Textarea::make('persyaratan')
                    ->label('Persyaratan')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Textarea::make('prosedur')
                    ->label('Prosedur (Opsional)')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),

                Textarea::make('timeline')
                    ->label('Timeline (Opsional)')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),

                Textarea::make('panduan')
                    ->label('Panduan (Opsional)')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),

                FileUpload::make('file_panduan')
                    ->label('File Panduan (Opsional)')
                    ->directory('panduan')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis')
                    ->sortable(),
                TextColumn::make('persyaratan')
                    ->limit(50),
                TextColumn::make('prosedur')
                    ->limit(50),
                TextColumn::make('timeline')
                    ->limit(50),
                TextColumn::make('panduan')
                    ->limit(50),
                TextColumn::make('file_panduan')
                    ->label('File Panduan')
                    ->formatStateUsing(fn($state) => Str::afterLast($state, '/'))
                    ->url(fn($record) => Storage::url($record->file_panduan))
                    ->openUrlInNewTab()
                    ->limit(50),



                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKetentuans::route('/'),
            'create' => Pages\CreateKetentuan::route('/create'),
            'edit' => Pages\EditKetentuan::route('/{record}/edit'),
        ];
    }
}
