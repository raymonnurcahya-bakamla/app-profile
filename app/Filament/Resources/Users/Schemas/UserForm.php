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
                Section::make(__('admin.sections.user_info'))
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('admin.fields.name'))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label(__('admin.fields.email'))
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            TextInput::make('password')
                                ->label(__('admin.fields.password'))
                                ->password()
                                ->revealable()
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->dehydrated(fn (?string $state): bool => filled($state)),
                            Select::make('role')
                                ->label(__('admin.fields.role'))
                                ->options(fn (): array => __('admin.options.user_roles'))
                                ->default('admin')
                                ->required(),
                            FileUpload::make('avatar')
                                ->label(__('admin.fields.avatar'))
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
