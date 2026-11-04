<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.sections.faq_info'))
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('topic_id')
                                ->label(__('admin.fields.topic'))
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('sort_order')
                                ->label(__('admin.fields.sort_order'))
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_active')
                                ->label(__('admin.fields.is_active'))
                                ->default(true),
                        ]),
                        TextInput::make('question')
                            ->label(__('admin.fields.faq_question'))
                            ->placeholder(__('admin.fields.faq_question_placeholder'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        RichEditor::make('answer')
                            ->label(__('admin.fields.answer_full'))
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
