<?php

namespace App\Filament\Resources\Menus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('admin.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->label(__('admin.fields.location'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'header' => 'primary',
                        'footer' => 'info',
                        'sidebar' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.options.menu_locations_simple.'.$state) ?: $state)
                    ->sortable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label(__('admin.fields.items_count'))
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_active')
                    ->label(__('admin.fields.active'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('admin.fields.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('location')
                    ->label(__('admin.fields.location'))
                    ->options(fn (): array => __('admin.options.menu_locations_simple')),
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
            ]);
    }
}
