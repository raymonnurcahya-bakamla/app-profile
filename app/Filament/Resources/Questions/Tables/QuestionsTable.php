<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->label(__('admin.fields.subject'))
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),
                TextColumn::make('name')
                    ->label(__('admin.fields.inquirer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('admin.fields.email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('topic.name')
                    ->label(__('admin.fields.topic'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('admin.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'answered' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.options.question_status.'.$state) ?: $state)
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('admin.fields.received_at'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('topic')
                    ->label(__('admin.fields.topic'))
                    ->relationship('topic', 'name'),
                SelectFilter::make('status')
                    ->label(__('admin.fields.status'))
                    ->options(fn (): array => __('admin.options.question_status')),
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
