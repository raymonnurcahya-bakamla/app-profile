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
                Section::make(__('admin.sections.setting_info'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('group')
                                ->label(__('admin.fields.group'))
                                ->default('general')
                                ->required(),
                            TextInput::make('key')
                                ->label(__('admin.fields.key'))
                                ->required()
                                ->unique(ignoreRecord: true),
                            Select::make('type')
                                ->label(__('admin.fields.type'))
                                ->options(fn (): array => __('admin.options.setting_types'))
                                ->default('text')
                                ->required()
                                ->live(),
                        ]),
                        TextInput::make('value')
                            ->label(__('admin.fields.value_text'))
                            ->visible(fn (Get $get): bool => $get('type') === 'text')
                            ->columnSpanFull(),
                        Textarea::make('value')
                            ->label(__('admin.fields.value_long'))
                            ->rows(5)
                            ->visible(fn (Get $get): bool => in_array($get('type'), ['textarea', 'json'], true))
                            ->columnSpanFull(),
                        FileUpload::make('value')
                            ->label(__('admin.fields.upload_image'))
                            ->disk('public')
                            ->directory('settings')
                            ->image()
                            ->visibility('public')
                            ->visible(fn (Get $get): bool => $get('type') === 'image')
                            ->columnSpanFull(),
                        Toggle::make('value')
                            ->label(__('admin.fields.active'))
                            ->visible(fn (Get $get): bool => $get('type') === 'boolean')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
