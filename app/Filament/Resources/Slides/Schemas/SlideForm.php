<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make('Konten Slide Banner')
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul Banner')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('description')
                                ->label('Deskripsi Singkat')
                                ->rows(3),
                            TextInput::make('link_url')
                                ->label('Tautan Target (Opsional)')
                                ->placeholder('https://... atau /halaman-tujuan')
                                ->maxLength(255),
                            FileUpload::make('image')
                                ->label('Gambar Slide (Landscape disarankan)')
                                ->disk('public')
                                ->directory('slides')
                                ->image()
                                ->imageEditor()
                                ->visibility('public')
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(2),

                    Section::make('Jadwal & Pengaturan')
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true),
                            TextInput::make('sort_order')
                                ->label('Urutan Tampilan')
                                ->numeric()
                                ->default(0),
                            DatePicker::make('start_date')
                                ->label('Mulai Ditampilkan')
                                ->helperText('Kosongkan jika selalu aktif'),
                            DatePicker::make('end_date')
                                ->label('Selesai Ditampilkan')
                                ->helperText('Kosongkan jika tanpa batas waktu'),
                        ])
                        ->columnSpan(1),
                ]),
            ]);
    }
}
