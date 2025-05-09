<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSkripsiResource\Pages;
use App\Models\PengajuanSkripsi;
use App\Models\Mahasiswa;
use App\Models\Ketentuan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class PengajuanSkripsiResource extends Resource
{
    protected static ?string $model = PengajuanSkripsi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationLabel = 'Kelola Pengajuan TA';
    protected static ?string $slug = 'kelola-pengajuan-ta';
    protected static ?string $navigationGroup = 'Kelola Pengajuan TA';

    public static function getNavigationGroup(): ?string
{
    return 'Kelola Pengajuan TA';
}

public static function getNavigationSort(): int
{
    return 1; // Nilai terkecil = posisi paling atas
}

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
        ->schema([
            Select::make('mahasiswa_id')
                ->label('Mahasiswa')
                ->options(Mahasiswa::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            Repeater::make('files')
                ->label('Unggah Berkas')
                ->schema([
                    Select::make('nama_berkas')
                        ->label('Jenis Berkas')
                        ->options(function () {
                            $ketentuan = Ketentuan::where('jenis', 'Skripsi')->pluck('persyaratan', 'persyaratan')->toArray();
                            // Memastikan tidak ada nilai null di array options
                            return array_filter($ketentuan, function($key, $value) {
                                return $key !== null && $value !== null;
                            }, ARRAY_FILTER_USE_BOTH);
                        })
                        ->required(),

                    FileUpload::make('file')
                        ->label('File')
                        ->disk('public')
                        ->directory('skripsi')
                        ->required(),
                ])
                ->minItems(1)
                ->maxItems(5),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('mahasiswa.name')
                    ->label('Nama Mahasiswa')
                    ->sortable()
                    ->searchable(),

                    TextColumn::make('files')
                    ->label('Berkas')
                    ->formatStateUsing(function ($state) {
                        $files = is_string($state) ? json_decode($state, true) : $state;
                        if (!is_array($files)) {
                            return '<em>Tidak ada berkas</em>';
                        }
                
                        return '<ol style="list-style-type: decimal; padding-right: 9rem; margin: 0;">' .
                            implode('', array_map(function ($file) {
                                if (!is_array($file) || !isset($file['file'], $file['nama_berkas'])) {
                                    return '<li><em>Berkas tidak valid</em></li>';
                                }
                                return "<li><a href='" . Storage::url($file['file']) . "' target='_blank' style='color: #3b82f6; text-decoration: underline;'>" . e($file['nama_berkas']) . "</a></li>";
                            }, $files)) .
                            '</ol>';
                    })
                    ->html(),
                
                
                    TextColumn::make('statuses')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        // Convert string ke array jika perlu
                        if (is_string($state)) {
                            $state = array_map('trim', explode(',', $state));
                        }
                    
                        $labelMap = [
                            'accepted' => ['label' => 'Disetujui', 'color' => '#10b981'], // Changed from 'Diterima' to 'Disetujui'
                            'rejected' => ['label' => 'Ditolak', 'color' => '#ef4444'],
                            'pending'  => ['label' => 'Menunggu', 'color' => '#6b7280'],
                        ];
                    
                        if (!is_array($state) || empty($state)) {
                            return '<em>Tidak ada status</em>';
                        }
                    
                        return '<ol style="list-style-type: decimal; list-style-position: inside; margin: 0; padding-left: 1.25rem;">' .
                        implode('', array_map(function ($item) use ($labelMap) {
                            $label = $labelMap[$item]['label'] ?? ucfirst($item);
                            $color = $labelMap[$item]['color'] ?? '#6b7280';
                            return "<li style='margin-bottom: 0.25rem;'>
                                        <span style='background-color: {$color}; color: white; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; display: inline-block;'>
                                            {$label}
                                        </span>
                                    </li>";
                        }, $state)) .
                        '</ol>';
                    })
                    ->html(),
                
                
                
                


            ])




            ->actions([
                Action::make('review')
                    ->label('Review Berkas')
                    ->form(function ($record) {
                        return [
                            Repeater::make('files')
                                ->label('Berkas')
                                ->schema([
                                    Placeholder::make('preview')
                                        ->label('Preview')
                                        ->content(function ($get) {
                                            $file = $get('file');
                                            $nama = $get('nama_berkas');
                                            $url = Storage::url($file);
                                            return new HtmlString("<a href=\"{$url}\" target=\"_blank\" class=\"text-blue-500 underline\">{$nama}</a>");
                                        })
                                        ->columnSpanFull(),

                                    Select::make('status')
                                        ->label('Status')
                                        ->options([
                                            'pending' => 'Menunggu',
                                            'accepted' => 'Diterima',
                                            'rejected' => 'Ditolak',
                                        ])
                                        ->default('pending')
                                        ->required(),

                                    Hidden::make('file'),
                                    Hidden::make('nama_berkas'),
                                ])
                                ->default(function () use ($record) {
                                    $files = is_string($record->files) ? json_decode($record->files, true) : ($record->files ?? []);
                                    $statuses = is_string($record->statuses) ? json_decode($record->statuses, true) : ($record->statuses ?? []);
                                    return collect($files)->map(function ($file, $i) use ($statuses) {
                                        return [
                                            'file' => $file['file'],
                                            'nama_berkas' => $file['nama_berkas'],
                                            'status' => $statuses[$i] ?? 'pending',
                                        ];
                                    })->toArray();
                                })
                                ->columns(1),
                        ];
                    })
                    ->action(function ($record, array $data) {
                        $statuses = collect($data['files'])->pluck('status')->toArray();
                        $record->update([
                            'statuses' => $statuses,
                        ]);
                    })
                    ->modalSubmitActionLabel('Simpan'),

                Action::make('accept_all')
                    ->label('Terima Semua')
                    ->action(function ($record) {
                        $files = is_string($record->files) ? json_decode($record->files, true) ?? [] : [];
                        $record->update([
                            'statuses' => array_fill(0, count($files), 'accepted'),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi')
                    ->modalSubheading('Apakah Anda yakin ingin menerima semua berkas ini?')
                    ->modalSubmitActionLabel('Terima Semua'),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanSkripsis::route('/'),
            'create' => Pages\CreatePengajuanSkripsi::route('/create'),
            'edit' => Pages\EditPengajuanSkripsi::route('/{record}/edit'),
        ];
    }
}
