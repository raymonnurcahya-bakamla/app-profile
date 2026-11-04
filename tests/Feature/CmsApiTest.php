<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_active_categories(): void
    {
        Category::factory()->create(['name' => 'Kategori Aktif', 'is_active' => true]);
        Category::factory()->create(['name' => 'Kategori Nonaktif', 'is_active' => false]);

        $response = $this->getJson(route('api.v1.categories.index'));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Kategori Aktif'])
            ->assertJsonMissing(['name' => 'Kategori Nonaktif']);
    }

    public function test_can_get_published_posts_and_filter_by_category(): void
    {
        $user = User::factory()->create();
        $cat1 = Category::factory()->create(['slug' => 'berita-daerah']);
        $cat2 = Category::factory()->create(['slug' => 'pengumuman']);

        Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $cat1->id,
            'title' => 'Berita Pagi Ini',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $cat2->id,
            'title' => 'Surat Edaran',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $cat1->id,
            'title' => 'Draft Belum Tayang',
            'status' => 'draft',
        ]);

        $response = $this->getJson(route('api.v1.posts.index', ['category' => 'berita-daerah']));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Berita Pagi Ini'])
            ->assertJsonMissing(['title' => 'Surat Edaran'])
            ->assertJsonMissing(['title' => 'Draft Belum Tayang']);
    }

    public function test_can_get_single_post_by_slug_and_increment_views(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'slug' => 'artikel-penting',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'view_count' => 5,
        ]);

        $response = $this->getJson(route('api.v1.posts.show', ['slug' => 'artikel-penting']));

        $response->assertOk()
            ->assertJsonPath('data.slug', 'artikel-penting')
            ->assertJsonPath('data.view_count', 6);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'view_count' => 6,
        ]);
    }

    public function test_can_get_single_page_by_slug(): void
    {
        $user = User::factory()->create();
        Page::factory()->create([
            'user_id' => $user->id,
            'title' => 'Visi dan Misi',
            'slug' => 'visi-misi',
            'status' => 'published',
        ]);

        $response = $this->getJson(route('api.v1.pages.show', ['slug' => 'visi-misi']));

        $response->assertOk()
            ->assertJsonPath('data.title', 'Visi dan Misi')
            ->assertJsonPath('data.slug', 'visi-misi');
    }

    public function test_can_get_active_slides(): void
    {
        Slide::factory()->create([
            'title' => 'Slide Aktif',
            'is_active' => true,
            'start_date' => now()->subDays(2)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
        ]);

        Slide::factory()->create([
            'title' => 'Slide Expired',
            'is_active' => true,
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->subDays(2)->toDateString(),
        ]);

        $response = $this->getJson(route('api.v1.slides.index'));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Slide Aktif'])
            ->assertJsonMissing(['title' => 'Slide Expired']);
    }

    public function test_can_get_menu_by_location(): void
    {
        $menu = Menu::create(['name' => 'Header Menu', 'location' => 'header', 'is_active' => true]);
        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => 'Beranda',
            'url' => '/',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.v1.menus.show', ['location' => 'header']));

        $response->assertOk()
            ->assertJsonPath('data.location', 'header')
            ->assertJsonFragment(['label' => 'Beranda']);
    }

    public function test_can_get_settings(): void
    {
        Setting::set('site_name', 'Portal Resmi', 'general', 'text');
        Setting::set('contact_phone', '0812345678', 'contact', 'text');

        $response = $this->getJson(route('api.v1.settings.index'));

        $response->assertOk()
            ->assertJsonPath('data.site_name', 'Portal Resmi')
            ->assertJsonPath('data.contact_phone', '0812345678');
    }
}
