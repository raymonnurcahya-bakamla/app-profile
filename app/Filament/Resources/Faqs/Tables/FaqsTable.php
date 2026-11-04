<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->label(__('admin.fields.question'))
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(60),
                TextColumn::make('topic.name')
                    ->label(__('admin.fields.topic'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('admin.fields.active'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label(__('admin.fields.sort_order'))
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('admin.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('topic')
                    ->label(__('admin.fields.topic'))
                    ->relationship('topic', 'name'),
                TernaryFilter::make('is_active')
                    ->label(__('admin.fields.is_active'))
                    ->boolean(),
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
            ->defaultSort('sort_order');
    }
}
