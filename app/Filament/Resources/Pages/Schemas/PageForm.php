<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make(__('admin.sections.page_content'))
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
                            RichEditor::make('content')
                                ->label(__('admin.fields.content'))
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(2),

                    Section::make(__('admin.sections.page_settings'))
                        ->schema([
                            Select::make('user_id')
                                ->label(__('admin.fields.editor'))
                                ->relationship('user', 'name')
                                ->default(fn () => auth()->id())
                                ->required(),
                            Select::make('status')
                                ->label(__('admin.fields.status'))
                                ->options(fn (): array => __('admin.options.page_status'))
                                ->default('published')
                                ->required(),
                            TextInput::make('sort_order')
                                ->label(__('admin.fields.sort_order'))
                                ->numeric()
                                ->default(0),
                            Toggle::make('show_in_menu')
                                ->label(__('admin.fields.show_in_menu'))
                                ->default(false),
                            FileUpload::make('image')
                                ->label(__('admin.fields.banner_cover'))
                                ->disk('public')
                                ->directory('pages')
                                ->image()
                                ->imageEditor()
                                ->visibility('public'),
                        ])
                        ->columnSpan(1),
                ]),
            ]);
    }
}
