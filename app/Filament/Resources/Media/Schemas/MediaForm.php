<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Unggah Berkas Media')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Berkas Media')
                            ->disk('public')
                            ->directory('media')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('file_name')
                            ->label('Nama Berkas')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('alt_text')
                            ->label('Teks Alternatif / Deskripsi')
                            ->maxLength(255),
                        Select::make('user_id')
                            ->label('Pengunggah')
                            ->relationship('user', 'name')
                            ->default(fn () => auth()->id())
                            ->required(),
                    ]),
            ]);
    }
}
