<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostsTable
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
                    ->sortable()
                    ->wrap()
                    ->limit(50),
                TextColumn::make('category.name')
                    ->label(__('admin.fields.category'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label(__('admin.fields.author'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label(__('admin.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'archived' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.options.post_status.'.$state) ?: $state)
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label(__('admin.fields.is_featured'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('view_count')
                    ->label(__('admin.fields.views'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label(__('admin.fields.published_time'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label(__('admin.fields.category'))
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->label(__('admin.fields.status'))
                    ->options(fn (): array => __('admin.options.post_status')),
                Filter::make('is_featured')
                    ->label(__('admin.fields.only_featured'))
                    ->query(fn (Builder $query) => $query->where('is_featured', true)),
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
