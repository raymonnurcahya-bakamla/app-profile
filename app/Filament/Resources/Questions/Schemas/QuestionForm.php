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
                Section::make(__('admin.sections.question_contact'))
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label(__('admin.fields.name'))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label(__('admin.fields.email'))
                                ->email()
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label(__('admin.fields.phone'))
                                ->maxLength(50),
                            Select::make('topic_id')
                                ->label(__('admin.fields.topic'))
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('status')
                                ->label(__('admin.fields.status'))
                                ->options(fn (): array => __('admin.options.question_status'))
                                ->default('pending')
                                ->required(),
                            TextInput::make('ip_address')
                                ->label(__('admin.fields.ip_address'))
                                ->disabled()
                                ->dehydrated(false),
                        ]),
                    ]),

                Section::make(__('admin.sections.question_body'))
                    ->schema([
                        TextInput::make('subject')
                            ->label(__('admin.fields.subject'))
                            ->required()
                            ->maxLength(255),
                        Textarea::make('body')
                            ->label(__('admin.fields.question_detail'))
                            ->rows(6)
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
