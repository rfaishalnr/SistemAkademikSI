<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Admin;
use App\Models\Dosen;
use Filament\Forms\Form;
use App\Models\UserDosen;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AdminResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AdminResource\RelationManagers;


class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Kelola Admin';
    protected static ?string $slug = 'kelola-admin';
    protected static ?string $navigationGroup = 'Kelola Users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()
                    ->placeholder('Masukan Nama')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                TextInput::make('email')->required()
                    ->placeholder('Masukan Email')
                    ->columnSpan([
                        'sm' => 2,
                        'xl' => 3,
                        '2xl' => 4,
                    ]),
                Select::make('roles')
                    ->relationship('roles', 'name'),
                TextInput::make('password')->required()->unique()
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
        // ->query(fn () => UserDosen::getCombinedQuery())


            ->columns([
                TextColumn::make('name')->sortable()->searchable()->label('Nama'),
                TextColumn::make('email')->sortable(),
                TextColumn::make('roles.name')->sortable()->label('Role'),
                TextColumn::make('password')->sortable()->label('Password'),
                // TextColumn::make('source')->label('Jabatan')->sortable(),
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
