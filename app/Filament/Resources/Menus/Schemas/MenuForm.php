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
                Section::make(__('admin.sections.menu_info'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label(__('admin.fields.name'))
                                ->placeholder('Contoh: Menu Navigasi Utama')
                                ->required()
                                ->maxLength(255),
                            Select::make('location')
                                ->label(__('admin.fields.location'))
                                ->options(fn (): array => __('admin.options.menu_locations'))
                                ->default('header')
                                ->required(),
                            Toggle::make('is_active')
                                ->label(__('admin.fields.is_active'))
                                ->default(true),
                        ]),
                    ]),

                Section::make(__('admin.sections.menu_items'))
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([
                                TextInput::make('label')
                                    ->label(__('admin.fields.label'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('url')
                                    ->label(__('admin.fields.url'))
                                    ->placeholder('/tentang-kami atau https://...')
                                    ->maxLength(255),
                                Select::make('type')
                                    ->label(__('admin.fields.type'))
                                    ->options(fn (): array => __('admin.options.menu_types'))
                                    ->default('custom')
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label(__('admin.fields.sort_order'))
                                    ->numeric()
                                    ->default(0),
                                Toggle::make('is_active')
                                    ->label(__('admin.fields.active'))
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
