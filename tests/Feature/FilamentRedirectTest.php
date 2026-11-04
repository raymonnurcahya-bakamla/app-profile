<?php

namespace Tests\Feature;

use Filament\Facades\Filament;
use Tests\TestCase;

class FilamentRedirectTest extends TestCase
{
    public function test_admin_panel_redirects_to_index_after_create_and_edit(): void
    {
        $panel = Filament::getPanel('admin');

        $this->assertTrue($panel->hasSpaMode());
        $this->assertSame('index', $panel->getResourceCreatePageRedirect());
        $this->assertSame('index', $panel->getResourceEditPageRedirect());
    }
}
