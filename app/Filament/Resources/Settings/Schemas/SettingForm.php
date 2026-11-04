<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengaturan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('group')
                                ->label('Grup')
                                ->default('general')
                                ->required(),
                            TextInput::make('key')
                                ->label('Key')
                                ->required()
                                ->unique(ignoreRecord: true),
                            Select::make('type')
                                ->label('Tipe Nilai')
                                ->options([
                                    'text' => 'Teks Singkat',
                                    'textarea' => 'Teks Panjang',
                                    'image' => 'Gambar / Logo',
                                    'boolean' => 'Boolean / Toggle',
                                    'json' => 'JSON Format',
                                ])
                                ->default('text')
                                ->required()
                                ->live(),
                        ]),
                        TextInput::make('value')
                            ->label('Nilai (Teks)')
                            ->visible(fn (Get $get): bool => $get('type') === 'text')
                            ->columnSpanFull(),
                        Textarea::make('value')
                            ->label('Nilai (Teks Panjang / JSON)')
                            ->rows(5)
                            ->visible(fn (Get $get): bool => in_array($get('type'), ['textarea', 'json'], true))
                            ->columnSpanFull(),
                        FileUpload::make('value')
                            ->label('Upload Gambar')
                            ->disk('public')
                            ->directory('settings')
                            ->image()
                            ->visibility('public')
                            ->visible(fn (Get $get): bool => $get('type') === 'image')
                            ->columnSpanFull(),
                        Toggle::make('value')
                            ->label('Aktifkan')
                            ->visible(fn (Get $get): bool => $get('type') === 'boolean')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
