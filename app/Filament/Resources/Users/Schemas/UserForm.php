<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Alamat Email')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            TextInput::make('password')
                                ->label('Kata Sandi')
                                ->password()
                                ->revealable()
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->dehydrated(fn (?string $state): bool => filled($state)),
                            Select::make('role')
                                ->label('Peran / Hak Akses')
                                ->options([
                                    'admin' => 'Admin (Akses Penuh)',
                                    'editor' => 'Editor (Kelola Konten)',
                                    'viewer' => 'Viewer (Lihat Saja)',
                                ])
                                ->default('admin')
                                ->required(),
                            FileUpload::make('avatar')
                                ->label('Foto Profil / Avatar')
                                ->disk('public')
                                ->directory('avatars')
                                ->image()
                                ->avatar()
                                ->visibility('public')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
