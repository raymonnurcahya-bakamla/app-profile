<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Questions\QuestionResource;
use App\Models\Question;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestQuestionsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string
    {
        return __('admin.widgets.latest_questions.heading');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Question::query()->with('topic')->latest()->limit(5))
            ->columns([
                TextColumn::make('subject')
                    ->label(__('admin.fields.subject'))
                    ->limit(40)
                    ->wrap(),
                TextColumn::make('name')
                    ->label(__('admin.fields.inquirer')),
                TextColumn::make('topic.name')
                    ->label(__('admin.fields.topic'))
                    ->badge()
                    ->color('info'),
                TextColumn::make('status')
                    ->label(__('admin.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'answered' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __('admin.options.question_status.'.$state) ?: $state),
                TextColumn::make('created_at')
                    ->label(__('admin.fields.received_at'))
                    ->since(),
            ])
            ->recordUrl(fn (Question $record): string => QuestionResource::getUrl('edit', ['record' => $record]))
            ->paginated(false);
    }
}
