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
                ->label('Jenis Ketentuan')
                ->options([
                    'KP' => 'Kerja Praktek (KP)',
                    'Skripsi' => 'Skripsi (TA)',
                ])
                ->required()
                ->columnSpanFull(),
            
            Textarea::make('persyaratan')
                ->label('Persyaratan')
                ->helperText('Hanya isi jika diperlukan')
                ->columnSpanFull(),
            
            Textarea::make('prosedur')
                ->label('Prosedur')
                ->helperText('Hanya isi jika diperlukan')
                ->columnSpanFull(),
            
            Textarea::make('timeline')
                ->label('Timeline')
                ->helperText('Hanya isi jika diperlukan')
                ->columnSpanFull(),
            
            Textarea::make('panduan')
                ->label('Nama Panduan')
                ->helperText('Hanya isi jika diperlukan')
                ->columnSpanFull(),
            
            FileUpload::make('file_panduan')
                ->label('File Panduan')
                ->helperText('Hanya unggah jika diperlukan')
                ->directory('panduan')
                ->columnSpanFull(),
            
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
