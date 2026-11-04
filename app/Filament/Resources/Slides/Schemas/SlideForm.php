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
                    Section::make(__('admin.sections.slide_content'))
                        ->schema([
                            TextInput::make('title')
                                ->label(__('admin.fields.title'))
                                ->required()
                                ->maxLength(255),
                            Textarea::make('description')
                                ->label(__('admin.fields.description'))
                                ->rows(3),
                            TextInput::make('link_url')
                                ->label(__('admin.fields.link_url'))
                                ->placeholder('https://... atau /halaman-tujuan')
                                ->maxLength(255),
                            FileUpload::make('image')
                                ->label(__('admin.fields.banner_image'))
                                ->disk('public')
                                ->directory('slides')
                                ->image()
                                ->imageEditor()
                                ->visibility('public')
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(2),

                    Section::make(__('admin.sections.slide_schedule'))
                        ->schema([
                            Toggle::make('is_active')
                                ->label(__('admin.fields.is_active'))
                                ->default(true),
                            TextInput::make('sort_order')
                                ->label(__('admin.fields.sort_order'))
                                ->numeric()
                                ->default(0),
                            DatePicker::make('start_date')
                                ->label(__('admin.fields.start_date'))
                                ->helperText(__('admin.fields.start_date_helper')),
                            DatePicker::make('end_date')
                                ->label(__('admin.fields.end_date'))
                                ->helperText(__('admin.fields.end_date_helper')),
                        ])
                        ->columnSpan(1),
                ]),
            ]);
    }
}
