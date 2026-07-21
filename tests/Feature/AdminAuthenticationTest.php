<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function 認証済みユーザーは管理画面を表示できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.index'));

        $response->assertStatus(200);
    }


    /** @test */
    public function 未認証ユーザーはログイン画面へリダイレクトされる(): void
    {
        $response = $this->get(route('admin.index'));

        $response->assertRedirect('/login');
    }
}
