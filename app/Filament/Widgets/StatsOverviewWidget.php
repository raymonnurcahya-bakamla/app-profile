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
            Stat::make('Total Artikel Terbit', $publishedPosts)
                ->description('Artikel publik yang aktif')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
            Stat::make('Total Pembaca (Views)', number_format($totalViews))
                ->description('Akumulasi pembaca artikel')
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary'),
            Stat::make('Pertanyaan Menunggu', $pendingQuestions)
                ->description('Perlu tindak lanjut / jawaban')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingQuestions > 0 ? 'warning' : 'gray'),
            Stat::make('Pertanyaan Selesai Dijawab', $answeredQuestions)
                ->description('Respon terkirim ke masyarakat')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),
        ];
    }
}
