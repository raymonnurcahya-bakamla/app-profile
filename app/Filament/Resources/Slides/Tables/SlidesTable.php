<?php

namespace App\Filament\Resources\Slides\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('admin.fields.cover'))
                    ->disk('public')
                    ->width(90)
                    ->height(45),
                TextColumn::make('title')
                    ->label(__('admin.fields.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('link_url')
                    ->label(__('admin.fields.target_url'))
                    ->limit(30),
                IconColumn::make('is_active')
                    ->label(__('admin.fields.active'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label(__('admin.fields.sort_order'))
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label(__('admin.fields.start_date_table'))
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('admin.fields.end_date_table'))
                    ->date()
                    ->sortable(),
            ])
            ->filters([
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
