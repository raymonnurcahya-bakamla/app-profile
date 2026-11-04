<?php

namespace App\Filament\Resources\QuestionTopics\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class QuestionTopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.sections.topic_info'))
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label(__('admin.fields.name'))
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
                            TextInput::make('icon')
                                ->label(__('admin.fields.icon'))
                                ->placeholder('heroicon-o-chat-bubble-left')
                                ->maxLength(255),
                            TextInput::make('sort_order')
                                ->label(__('admin.fields.sort_order'))
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_active')
                                ->label(__('admin.fields.is_active'))
                                ->default(true)
                                ->columnSpanFull(),
                            Textarea::make('description')
                                ->label(__('admin.fields.topic_description'))
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
