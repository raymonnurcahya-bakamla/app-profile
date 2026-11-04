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
                    Section::make('Konten Halaman')
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul Halaman')
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
                            RichEditor::make('content')
                                ->label('Isi Konten Halaman')
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(2),

                    Section::make('Pengaturan Tampilan')
                        ->schema([
                            Select::make('user_id')
                                ->label('Penulis / Editor')
                                ->relationship('user', 'name')
                                ->default(fn () => auth()->id())
                                ->required(),
                            Select::make('status')
                                ->label('Status Publikasi')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Dipublikasikan',
                                ])
                                ->default('published')
                                ->required(),
                            TextInput::make('sort_order')
                                ->label('Urutan Tampilan')
                                ->numeric()
                                ->default(0),
                            Toggle::make('show_in_menu')
                                ->label('Tampilkan pada Navigasi Menu')
                                ->default(false),
                            FileUpload::make('image')
                                ->label('Gambar Banner / Cover')
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
