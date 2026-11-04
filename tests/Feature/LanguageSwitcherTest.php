<?php

namespace Tests\Feature;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Questions\QuestionResource;
use App\Filament\Resources\Settings\SettingResource;
use App\Filament\Widgets\LatestPostsWidget;
use App\Http\Middleware\SetLocale;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    use RefreshDatabase;

    public function test_switch_language_route_updates_session_locale_and_redirects(): void
    {
        $response = $this->from('/admin')
            ->get(route('switch-language', ['locale' => 'en']));

        $response->assertRedirect('/admin');
        $response->assertSessionHas('locale', 'en');

        $response = $this->from('/admin/posts')
            ->get(route('switch-language', ['locale' => 'id']));

        $response->assertRedirect('/admin/posts');
        $response->assertSessionHas('locale', 'id');
    }

    public function test_switch_language_ignores_invalid_locales(): void
    {
        $response = $this->from('/admin')
            ->get(route('switch-language', ['locale' => 'fr']));

        $response->assertRedirect('/admin');
        $this->assertNotSame('fr', session('locale'));
    }

    public function test_set_locale_middleware_applies_session_locale(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['locale' => 'en'])
            ->get('/admin');

        $this->assertSame('en', App::getLocale());

        $this->actingAs($user)
            ->withSession(['locale' => 'id'])
            ->get('/admin');

        $this->assertSame('id', App::getLocale());
    }

    public function test_language_switcher_view_renders_with_locales(): void
    {
        $rendered = (string) view('filament.components.language-switcher')->render();

        $this->assertStringContainsString('Bahasa Indonesia', $rendered);
        $this->assertStringContainsString('English', $rendered);
        $this->assertStringContainsString(route('switch-language', ['locale' => 'id']), $rendered);
        $this->assertStringContainsString(route('switch-language', ['locale' => 'en']), $rendered);
    }

    public function test_admin_panel_configures_language_switcher_and_middleware(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertContains(SetLocale::class, $panel->getMiddleware());

        Filament::setCurrentPanel($panel);
        Filament::bootCurrentPanel();

        $this->assertTrue(FilamentView::hasRenderHook(PanelsRenderHook::USER_MENU_BEFORE));
    }

    public function test_navigation_and_model_labels_adapt_to_active_locale(): void
    {
        App::setLocale('id');

        $this->assertSame('CMS Konten', CategoryResource::getNavigationGroup());
        $this->assertSame('Kategori', CategoryResource::getModelLabel());
        $this->assertSame('CMS Konten', PostResource::getNavigationGroup());
        $this->assertSame('Artikel & Berita', PostResource::getNavigationLabel());
        $this->assertSame('Pertanyaan Masyarakat', QuestionResource::getNavigationGroup());
        $this->assertSame('Pengaturan & Sistem', SettingResource::getNavigationGroup());

        App::setLocale('en');

        $this->assertSame('Content CMS', CategoryResource::getNavigationGroup());
        $this->assertSame('Category', CategoryResource::getModelLabel());
        $this->assertSame('Content CMS', PostResource::getNavigationGroup());
        $this->assertSame('Posts & Articles', PostResource::getNavigationLabel());
        $this->assertSame('Public Inquiries', QuestionResource::getNavigationGroup());
        $this->assertSame('Settings & System', SettingResource::getNavigationGroup());
    }

    public function test_widgets_and_forms_adapt_to_active_locale(): void
    {
        App::setLocale('id');
        $latestPostsWidget = new LatestPostsWidget;
        $this->assertSame('Artikel & Publikasi Terakhir', $latestPostsWidget->getTableHeading());
        $this->assertSame('Dipublikasikan', __('admin.options.post_status.published'));

        App::setLocale('en');
        $this->assertSame('Latest Articles & Publications', $latestPostsWidget->getTableHeading());
        $this->assertSame('Published', __('admin.options.post_status.published'));
    }
}
