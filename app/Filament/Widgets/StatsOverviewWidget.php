<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Question;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $publishedPosts = Post::published()->count();
        $totalViews = Post::published()->sum('view_count');
        $pendingQuestions = Question::where('status', 'pending')->count();
        $answeredQuestions = Question::where('status', 'answered')->count();

        return [
            Stat::make(__('admin.widgets.stats.total_posts'), $publishedPosts)
                ->description(__('admin.widgets.stats.total_posts_desc'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
            Stat::make(__('admin.widgets.stats.total_views'), number_format($totalViews))
                ->description(__('admin.widgets.stats.total_views_desc'))
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary'),
            Stat::make(__('admin.widgets.stats.pending_questions'), $pendingQuestions)
                ->description(__('admin.widgets.stats.pending_questions_desc'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingQuestions > 0 ? 'warning' : 'gray'),
            Stat::make(__('admin.widgets.stats.answered_questions'), $answeredQuestions)
                ->description(__('admin.widgets.stats.answered_questions_desc'))
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),
        ];
    }
}
