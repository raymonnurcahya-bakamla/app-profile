<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestPostsWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Artikel & Publikasi Terakhir';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->with('category')->latest('published_at')->limit(5))
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Artikel')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('view_count')
                    ->label('Views')
                    ->numeric(),
                TextColumn::make('published_at')
                    ->label('Waktu Tayang')
                    ->since(),
            ])
            ->recordUrl(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
            ->paginated(false);
    }
}
