<?php

namespace Tests\Feature;

use Tests\TestCase;

class UiFoundationTest extends TestCase
{
    public function test_home_and_login_render_successfully(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Signature')
            ->assertSee('Welcome Back')
            ->assertSee('Sign In to Signature POS');

        $this->get('/login')
            ->assertOk()
            ->assertSee('Welcome Back');
    }

    public function test_ui_foundation_preview_renders_each_portal_shell(): void
    {
        $this->get('/ui-foundation/hq')
            ->assertOk()
            ->assertSee('HQ Global Scope')
            ->assertSee('Low Stock');

        $this->get('/ui-foundation/manager')
            ->assertOk()
            ->assertSee('Branch Manager')
            ->assertSee('Branch Inventory');

        $this->get('/ui-foundation/cashier')
            ->assertOk()
            ->assertSee('Branch Cashier')
            ->assertSee('POS Terminal');
    }

    public function test_ui_foundation_rejects_unknown_portal(): void
    {
        $this->get('/ui-foundation/unknown')->assertNotFound();
    }
}
