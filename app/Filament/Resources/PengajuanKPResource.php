<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanKPResource\Pages;
use App\Models\PengajuanKP;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;

class PengajuanKPResource extends Resource
{
    protected static ?string $model = PengajuanKP::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Kelola Pengajuan KP';
    protected static ?string $slug = 'kelola-pengajuan-kp';
    protected static ?string $navigationGroup = 'Kelola Pengajuan KP';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('mahasiswa_id')
                ->label('Mahasiswa')
                ->options(Mahasiswa::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),

            FileUpload::make('files')
                ->multiple()
                ->disk('public')
                ->directory('pengajuan_kp')
                ->required(),
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
                    ->formatStateUsing(
                        fn($state) =>
                            '<ol style="list-style-type: decimal; padding-right: 10rem; margin: 0; text-align: left;">' .
                            
                            implode('', array_map(
                                fn($file) => "<li><a href='" . Storage::url($file) . "' target='_blank' style='color: #3b82f6; text-decoration: underline;'>" . basename($file) . "</a></li>",
                                is_array($state) ? $state : (is_string($state) ? json_decode($state, true) ?? [] : [])
                            )) .
                            '</ol>'
                    )
                    ->html(),
                
                
                    TextColumn::make('statuses')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        // Map status ke label dan warna
                        $labelMap = [
                            'accepted' => ['label' => 'Disetujui', 'color' => '#10b981'], // Changed from 'Diterima' to 'Disetujui'
                            'rejected' => ['label' => 'Ditolak', 'color' => '#ef4444'],
                            'pending'  => ['label' => 'Menunggu', 'color' => '#6b7280'],
                        ];
                    
                        // Normalisasi isi state
                        if (is_string($state) && str_contains($state, ',')) {
                            $items = array_map('trim', explode(',', $state));
                        } elseif (is_array($state)) {
                            $items = $state;
                        } else {
                            $items = [print_r($state, true)];
                        }
                    
                        // Buat list status dengan style badge
                        return '<ol style="list-style-type: decimal; list-style-position: inside; margin: 0; padding-left: 1.25rem;">' .
                        implode('', array_map(function ($item) use ($labelMap) {
                            $label = $labelMap[$item]['label'] ?? ucfirst($item);
                            $color = $labelMap[$item]['color'] ?? '#6b7280';
                            return "<li style='margin-bottom: 0.25rem;'>
                                        <span style='background-color: {$color}; color: white; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; display: inline-block;'>
                                            {$label}
                                        </span>
                                    </li>";
                        }, $items)) .
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

                                            if (!is_string($file) || !str_contains($file, '.')) {
                                                return 'File tidak valid';
                                            }

                                            $url = \Illuminate\Support\Facades\Storage::url($file);
                                            $filename = basename($file);

                                            // Gunakan entitas HTML &quot; untuk tanda kutip agar aman
                                            return new \Illuminate\Support\HtmlString(
                                                "<a href=\"{$url}\" target=\"_blank\" class=\"text-blue-500 underline\">{$filename}</a>"
                                            );
                                        })
                                        ->columnSpanFull()
                                        ->extraAttributes(['class' => 'block']),






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
                                ])
                                ->default(function () use ($record) {
                                    $files = is_string($record->files) ? json_decode($record->files, true) ?? [] : ($record->files ?? []);
                                    $statuses = is_string($record->statuses) ? json_decode($record->statuses, true) ?? [] : ($record->statuses ?? []);

                                    return collect($files)
                                        ->map(function ($file, $index) use ($statuses) {
                                            return [
                                                'file' => $file,
                                                'status' => $statuses[$index] ?? 'pending', // pakai indeks
                                            ];
                                        })
                                        ->toArray();
                                })
                                ->columns(1)


                        ];
                    })
                    ->action(function ($record, array $data) {
                        $newStatuses = [];

                        foreach ($data['files'] as $item) {
                            $newStatuses[] = $item['status']; // ⬅️ Tambah ke array indexed
                        }

                        $record->update([
                            'statuses' => $newStatuses,
                        ]);
                    })
                    ->modalSubmitActionLabel('Simpan'),

                Action::make('accept_all')
                    ->label('Terima Semua')
                    ->action(function ($record) {
                        $files = is_array($record->files) ? $record->files : json_decode($record->files, true) ?? [];

                        $statuses = collect($files)
                            ->mapWithKeys(fn($file) => [$file => 'accepted'])
                            ->toArray();

                        $record->update([
                            'statuses' => array_fill(0, count($files), 'accepted'),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi')
                    ->modalDescription('Apakah Anda yakin ingin menerima semua berkas ini?')
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
            'index' => Pages\ListPengajuanKPS::route('/'),
            'create' => Pages\CreatePengajuanKP::route('/create'),
            'edit' => Pages\EditPengajuanKP::route('/{record}/edit'),
        ];
    }
}
