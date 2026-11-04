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
                    Section::make('Konten Artikel')
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul Artikel')
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
                            Textarea::make('summary')
                                ->label('Ringkasan / Sinopsis')
                                ->rows(3)
                                ->helperText('Ringkasan singkat yang akan ditampilkan pada daftar artikel.'),
                            RichEditor::make('content')
                                ->label('Isi Artikel')
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(2),

                    Section::make('Publikasi & Meta')
                        ->schema([
                            Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('user_id')
                                ->label('Penulis')
                                ->relationship('user', 'name')
                                ->default(fn () => auth()->id())
                                ->required(),
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Dipublikasikan',
                                    'archived' => 'Diarsipkan',
                                ])
                                ->default('draft')
                                ->required()
                                ->live(),
                            DateTimePicker::make('published_at')
                                ->label('Waktu Tayang')
                                ->default(now())
                                ->visible(fn (Get $get): bool => $get('status') === 'published'),
                            Toggle::make('is_featured')
                                ->label('Tandai sebagai Unggulan')
                                ->default(false),
                            FileUpload::make('image')
                                ->label('Gambar Sampul (Thumbnail)')
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
