<?php

namespace App\Filament\Resources\QuestionTopics\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class QuestionTopicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('admin.fields.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('admin.fields.slug'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label(__('admin.fields.questions_count'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('faqs_count')
                    ->counts('faqs')
                    ->label(__('admin.fields.faqs_count'))
                    ->badge()
                    ->color('success')
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
