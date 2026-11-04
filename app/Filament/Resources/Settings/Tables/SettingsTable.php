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
                    ->label('Grup')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('key')
                    ->label('Key')
                    ->sortable()
                    ->searchable()
                    ->copyable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('Grup')
                    ->options([
                        'general' => 'General',
                        'profile' => 'Profil',
                        'contact' => 'Kontak',
                        'social' => 'Media Sosial',
                    ]),
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'text' => 'Teks Singkat',
                        'textarea' => 'Teks Panjang',
                        'image' => 'Gambar',
                        'boolean' => 'Boolean',
                        'json' => 'JSON',
                    ]),
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
