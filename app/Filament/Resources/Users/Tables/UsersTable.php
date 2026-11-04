<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label(__('admin.fields.avatar'))
                    ->circular()
                    ->disk('public'),
                TextColumn::make('name')
                    ->label(__('admin.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('admin.fields.email'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('role')
                    ->label(__('admin.fields.role'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'editor' => 'warning',
                        'viewer' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.options.user_roles_simple.'.$state) ?: $state)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('admin.fields.registered_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label(__('admin.fields.role'))
                    ->options(fn (): array => __('admin.options.user_roles_simple')),
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
