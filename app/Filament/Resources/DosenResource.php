<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DosenResource\Pages;
use App\Filament\Resources\DosenResource\RelationManagers;
use App\Models\Dosen;
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

class DosenResource extends Resource
{
    protected static ?string $model = Dosen::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Kelola Dosen';
    protected static ?string $slug = 'kelola-dosen';
    protected static ?string $navigationGroup = 'Kelola Users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static ?string $label = 'Kelola Dosen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->placeholder('Masukan Nama Dosen'),
                TextInput::make('nidn')
                    ->required()
                    // ->unique()
                    ->numeric()
                    ->placeholder('Masukan NIDN Dosen')
                    ->label('NIDN'),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->placeholder('Masukan Email Dosen'),
                TextInput::make('nomor_hp')
                    ->required()
                    ->tel()
                    ->placeholder('Masukan Nomor HP'),
                TextInput::make('prodi')
                    ->required()
                    ->placeholder('Masukan Prodi Dosen'),
                Select::make('peran')
                    ->options([
                        'pembimbing' => 'Pembimbing',
                        // 'penguji' => 'Penguji',
                    ])
                    ->placeholder('Masukan Peran Dosen')
                    ->required(),
                TextInput::make('password')->required()->unique(false)
                    ->placeholder('Masukan Password')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nidn')
                    ->sortable()
                    ->searchable()
                    ->label('NIDN'),
                TextColumn::make('email')
                    ->sortable()
                    ->copyable(),
                TextColumn::make('password')
                    ->sortable(),
                TextColumn::make('prodi')
                    ->label('Program Studi')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('peran')
                    ->sortable(),
                TextColumn::make('nomor_hp')
                    ->label('Nomor HP')
                    ->sortable()
                    ->searchable(),


                // TextColumn::make('source')->label('Tipe Pengguna'),
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
            'index' => Pages\ListDosens::route('/'),
            'create' => Pages\CreateDosen::route('/create'),
            'edit' => Pages\EditDosen::route('/{record}/edit'),
        ];
    }
}
