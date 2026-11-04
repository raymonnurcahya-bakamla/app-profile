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

    public function getTableHeading(): string
    {
        return __('admin.widgets.latest_posts.heading');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Post::query()->with('category')->latest('published_at')->limit(5))
            ->columns([
                TextColumn::make('title')
                    ->label(__('admin.fields.title'))
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('category.name')
                    ->label(__('admin.fields.category'))
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label(__('admin.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.options.post_status.'.$state) ?: $state),
                TextColumn::make('view_count')
                    ->label(__('admin.fields.views'))
                    ->numeric(),
                TextColumn::make('published_at')
                    ->label(__('admin.fields.published_at'))
                    ->since(),
            ])
            ->recordUrl(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
            ->paginated(false);
    }
}
