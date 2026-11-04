<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin.sections.media_upload'))
                    ->schema([
                        FileUpload::make('file_path')
                            ->label(__('admin.fields.file_path'))
                            ->disk('public')
                            ->directory('media')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('file_name')
                            ->label(__('admin.fields.file_name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('alt_text')
                            ->label(__('admin.fields.alt_text'))
                            ->maxLength(255),
                        Select::make('user_id')
                            ->label(__('admin.fields.uploader'))
                            ->relationship('user', 'name')
                            ->default(fn () => auth()->id())
                            ->required(),
                    ]),
            ]);
    }
}
