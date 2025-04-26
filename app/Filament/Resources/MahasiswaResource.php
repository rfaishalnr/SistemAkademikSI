<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
use App\Filament\Resources\MahasiswaResource\RelationManagers;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\User;


class MahasiswaResource extends Resource
{

    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Kelola Mahasiswa';
    protected static ?string $slug = 'kelola-mahasiswa';
    protected static ?string $navigationGroup = 'Kelola Users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static ?string $label = 'Kelola Mahasiswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()
                    ->placeholder('Masukan Nama Mahasiswa')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                TextInput::make('npm')->required()->unique(ignoreRecord: true)
                    ->integer()
                    ->placeholder('Masukan NPM Mahasiswa')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                TextInput::make('email')->required()->email()
                    ->placeholder('Masukan Npm Mahasiswa')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                TextInput::make('password')->required()
                    ->dehydrateStateUsing(fn($state) => bcrypt($state))
                    ->placeholder('Masukan Password')
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
                // TextColumn::make('id')->sortable()->copyable(),
                TextColumn::make('name')
                ->label('Nama')
                ->sortable()
                ->searchable()
                ->copyable(),
                TextColumn::make('npm')
                ->label('NPM')
                ->sortable()
                ->searchable()
                ->copyable(),
                TextColumn::make('email')->sortable()->copyable(),
                TextColumn::make('password')->sortable()->copyable(),
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
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }

    public static function beforeCreate($data)
{
    // Simpan akun ke tabel users
    // User::create([
    //     'name' => $data['name'],
    //     'email' => $data['email'],
    //     'password' => $data['password'], 
    //     'role' => 'mahasiswa', 
    // ]);
}
}
