<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make(__('admin.sections.post_content'))
                        ->schema([
                            TextInput::make('title')
                                ->label(__('admin.fields.title'))
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                    'slug',
                                    Str::slug($state ?? '')
                                )),
                            TextInput::make('slug')
                                ->label(__('admin.fields.slug'))
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            Textarea::make('summary')
                                ->label(__('admin.fields.summary'))
                                ->rows(3)
                                ->helperText(__('admin.fields.summary_helper')),
                            RichEditor::make('content')
                                ->label(__('admin.fields.content'))
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(2),

                    Section::make(__('admin.sections.post_meta'))
                        ->schema([
                            Select::make('category_id')
                                ->label(__('admin.fields.category'))
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('user_id')
                                ->label(__('admin.fields.author'))
                                ->relationship('user', 'name')
                                ->default(fn () => auth()->id())
                                ->required(),
                            Select::make('status')
                                ->label(__('admin.fields.status'))
                                ->options(fn (): array => __('admin.options.post_status'))
                                ->default('draft')
                                ->required()
                                ->live(),
                            DateTimePicker::make('published_at')
                                ->label(__('admin.fields.published_at'))
                                ->default(now())
                                ->visible(fn (Get $get): bool => $get('status') === 'published'),
                            Toggle::make('is_featured')
                                ->label(__('admin.fields.mark_as_featured'))
                                ->default(false),
                            FileUpload::make('image')
                                ->label(__('admin.fields.thumbnail'))
                                ->disk('public')
                                ->directory('posts')
                                ->image()
                                ->imageEditor()
                                ->visibility('public'),
                        ])
                        ->columnSpan(1),
                ]),
            ]);
    }
}
