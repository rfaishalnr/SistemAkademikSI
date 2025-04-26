<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PilihDospemSkripsiResource\Pages;
use App\Models\PengajuanSkripsi;
use App\Models\Dosen;
use App\Models\PilihDospemSkripsi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class PilihDospemSkripsiResource extends Resource
{
    protected static ?string $model = PilihDospemSkripsi::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Review Dosen Pembimbing 1';
    protected static ?string $pluralModelLabel = 'Review Dosen Pembimbing 1';
    protected static ?string $navigationGroup = 'Kelola Pengajuan TA';

    public static function getEloquentQuery(): EloquentBuilder
    {
        $user = Auth::user();
    
        if ($user && $user->role === 'dosen') {
            // Cari dosen berdasarkan email yang sama dengan user
            $dosen = Dosen::where('email', $user->email)->first();
            
            if ($dosen) {
                return parent::getEloquentQuery()
                    ->where('dosen_pembimbing_id', $dosen->id);
            }
        }
    
        return parent::getEloquentQuery();
    }
    
    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        
        if ($user && $user->role === 'dosen') {
            $dosen = Dosen::where('email', $user->email)->first();
            
            if ($dosen) {
                return static::getModel()::where('dosen_pembimbing_id', $dosen->id)->count();
            }
        }
        
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dosen_pembimbing_id')
                    ->label('Dosen Pembimbing')
                    ->options(Dosen::where('peran', 'Pembimbing')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('status_pembimbing')
                    ->label('Status Pembimbing')
                    ->options([
                        'pending' => 'Menunggu',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('catatan_pembimbing')
                    ->label('Catatan')
                    ->rows(3)
                    ->maxLength(1000)
                    ->placeholder('Catatan atau alasan penolakan jika ada...')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.name')
                    ->label('Nama Mahasiswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dosenPembimbing.name')
                    ->label('Dosen Pembimbing')
                    ->placeholder('Belum dipilih'),

                Tables\Columns\TextColumn::make('status_pembimbing')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        $labelMap = [
                            'pending'  => ['label' => 'Menunggu', 'color' => '#6b7280'],
                            'accepted' => ['label' => 'Disetujui', 'color' => '#10b981'],
                            'rejected' => ['label' => 'Ditolak', 'color' => '#ef4444'],
                        ];

                        $label = $labelMap[$state]['label'] ?? ucfirst($state);
                        $color = $labelMap[$state]['color'] ?? '#6b7280';

                        return "<span style='background-color: {$color}; color: white; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; display: inline-block;'>
                                    {$label}
                                </span>";
                    })
                    ->html(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pembimbing')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Action::make('review_pembimbing_1')
                    ->label('Review Pembimbing 1')
                    ->icon('heroicon-o-check-circle')
                    ->form([
                        Forms\Components\Select::make('status_pembimbing')
                            ->label('Status Pembimbing')
                            ->options([
                                'accepted' => 'Terima',
                                'rejected' => 'Tolak',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Textarea::make('catatan_pembimbing')
                            ->label('Catatan')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Catatan atau alasan penolakan (opsional)'),
                    ])
                    ->action(function (PilihDospemSkripsi $record, array $data) {
                        $record->update([
                            'status_pembimbing' => $data['status_pembimbing'],
                            'catatan_pembimbing' => $data['catatan_pembimbing'],
                        ]);
                    })
                    ->modalHeading('Review Pembimbing 1')
                    ->modalSubmitActionLabel('Simpan Review')
                    ->modalWidth('md'),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPilihDospemSkripsis::route('/'),
            'create' => Pages\CreatePilihDospemSkripsi::route('/create'),
            'edit' => Pages\EditPilihDospemSkripsi::route('/{record}/edit'),
        ];
    }
}