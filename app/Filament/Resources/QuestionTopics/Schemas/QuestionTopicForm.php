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
                Section::make('Informasi Topik Pertanyaan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Topik')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                    'slug',
                                    Str::slug($state ?? '')
                                )),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            TextInput::make('icon')
                                ->label('Icon (Opsional)')
                                ->placeholder('heroicon-o-chat-bubble-left')
                                ->maxLength(255),
                            TextInput::make('sort_order')
                                ->label('Urutan Tampilan')
                                ->numeric()
                                ->default(0),
                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true)
                                ->columnSpanFull(),
                            Textarea::make('description')
                                ->label('Deskripsi Topik')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
