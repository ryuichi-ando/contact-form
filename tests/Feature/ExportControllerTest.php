<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Contact;
use App\Models\User;

class ExportControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function 管理者はフィルタ条件付きでCSVをダウンロードできる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $category = Category::factory()->create();

        Contact::factory()->create([
            'first_name' => '山田',
            'category_id' => $category->id,
        ]);

        Contact::factory()->create([
            'first_name' => '佐藤',
            'category_id' => $category->id,
        ]);

        $response = $this->get('/contacts/export?keyword=山田');

        $response->assertStatus(200);

        $response->assertHeader(
            'content-type',
            'text/csv; charset=UTF-8'
        );

        $response->assertSee('山田');
        $response->assertDontSee('佐藤');
    }

    /** @test */
    public function フィルタ未指定なら新着順でCSVをダウンロードできる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $category = Category::factory()->create();

        $old = Contact::factory()->create([
            'first_name' => '古いデータ',
            'category_id' => $category->id,
            'created_at' => now()->subDay(),
        ]);

        $new = Contact::factory()->create([
            'first_name' => '新しいデータ',
            'category_id' => $category->id,
            'created_at' => now(),
        ]);

        $response = $this->get('/contacts/export');

        $response->assertStatus(200);

        $response->assertHeader(
            'content-type',
            'text/csv; charset=UTF-8'
        );

        $content = $response->streamedContent();

        $this->assertTrue(
            strpos($content, '新しいデータ') <
            strpos($content, '古いデータ')
        );
    }

    /** @test */
    public function 未認証ユーザーはCSVをダウンロードできない(): void
    {
        $response = $this->get('/contacts/export');

        $response->assertRedirect(route('login'));
    }
}
