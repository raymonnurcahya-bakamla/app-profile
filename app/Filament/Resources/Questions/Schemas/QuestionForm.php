<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Penanya & Status')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Email Penanya')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Nomor Telepon/WA')
                                ->maxLength(50),
                            Select::make('topic_id')
                                ->label('Topik Pertanyaan')
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('status')
                                ->label('Status Pertanyaan')
                                ->options([
                                    'pending' => 'Menunggu Jawaban',
                                    'answered' => 'Sudah Dijawab',
                                    'rejected' => 'Ditolak / Tidak Relevan',
                                ])
                                ->default('pending')
                                ->required(),
                            TextInput::make('ip_address')
                                ->label('Alamat IP')
                                ->disabled()
                                ->dehydrated(false),
                        ]),
                    ]),

                Section::make('Isi Pertanyaan')
                    ->schema([
                        TextInput::make('subject')
                            ->label('Subjek / Judul Pertanyaan')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('body')
                            ->label('Detail Pertanyaan')
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
