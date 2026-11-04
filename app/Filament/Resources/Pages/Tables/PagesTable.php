<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('admin.fields.cover'))
                    ->disk('public')
                    ->circular(),
                TextColumn::make('title')
                    ->label(__('admin.fields.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('admin.fields.slug'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->label(__('admin.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.options.page_status.'.$state) ?: $state)
                    ->sortable(),
                IconColumn::make('show_in_menu')
                    ->label(__('admin.fields.in_menu'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label(__('admin.fields.sort_order'))
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('admin.fields.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('admin.fields.status'))
                    ->options(fn (): array => __('admin.options.page_status')),
                TernaryFilter::make('show_in_menu')
                    ->label(__('admin.fields.show_in_menu'))
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
