<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Contact;
use App\Models\User;

class AdminShowTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function 管理者はお問い合わせ詳細を表示できる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $category = Category::factory()->create([
            'content' => '商品のお届けについて',
        ]);

        $contact = Contact::factory()->create([
            'category_id' => $category->id,
            'first_name' => '太郎',
            'last_name' => '山田',
            'email' => 'yamada@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'building' => '○○ビル',
            'detail' => 'お問い合わせ内容です。',
        ]);

        $response = $this->get(route('admin.show', $contact));

        $response->assertStatus(200);

        $response->assertViewIs('admin.show');

        $response->assertViewHas('contact');

        $response->assertSee('山田');
        $response->assertSee('太郎');
        $response->assertSee('yamada@example.com');
        $response->assertSee('09012345678');
        $response->assertSee('東京都');
        $response->assertSee('○○ビル');
        $response->assertSee('お問い合わせ内容です。');
        $response->assertSee('商品のお届けについて');
    }
}
