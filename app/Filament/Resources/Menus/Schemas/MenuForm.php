<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Menu')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Nama Menu')
                                ->placeholder('Contoh: Menu Navigasi Utama')
                                ->required()
                                ->maxLength(255),
                            Select::make('location')
                                ->label('Lokasi Penempatan')
                                ->options([
                                    'header' => 'Header (Navigasi Atas)',
                                    'footer' => 'Footer (Navigasi Bawah)',
                                    'sidebar' => 'Sidebar (Bilah Samping)',
                                ])
                                ->default('header')
                                ->required(),
                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true),
                        ]),
                    ]),

                Section::make('Daftar Item Menu (Navigasi)')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('label')
                                    ->label('Label Menu')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('url')
                                    ->label('URL / Tautan')
                                    ->placeholder('/tentang-kami atau https://...')
                                    ->maxLength(255),
                                Select::make('type')
                                    ->label('Jenis')
                                    ->options([
                                        'custom' => 'Tautan Kustom',
                                        'page' => 'Halaman Statis',
                                        'category' => 'Kategori Post',
                                        'post' => 'Artikel Tertentu',
                                    ])
                                    ->default('custom')
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                            ])
                            ->columns(5)
                            ->orderColumn('sort_order')
                            ->defaultItems(1)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
