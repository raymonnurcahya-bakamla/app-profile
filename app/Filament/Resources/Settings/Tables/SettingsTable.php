<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->label(__('admin.fields.group'))
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('key')
                    ->label(__('admin.fields.key'))
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('type')
                    ->label(__('admin.fields.type'))
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => __('admin.options.setting_types_simple.'.$state) ?: $state),
                TextColumn::make('value')
                    ->label(__('admin.fields.value'))
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label(__('admin.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label(__('admin.fields.group'))
                    ->options(fn (): array => __('admin.options.setting_groups')),
                SelectFilter::make('type')
                    ->label(__('admin.fields.type'))
                    ->options(fn (): array => __('admin.options.setting_types_simple')),
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
