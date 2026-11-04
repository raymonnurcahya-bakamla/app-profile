<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label(__('admin.fields.preview'))
                    ->disk('public')
                    ->height(50),
                TextColumn::make('file_name')
                    ->label(__('admin.fields.file_name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('alt_text')
                    ->label(__('admin.fields.alt_text'))
                    ->searchable()
                    ->limit(30),
                TextColumn::make('user.name')
                    ->label(__('admin.fields.uploader'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('admin.fields.uploaded_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
