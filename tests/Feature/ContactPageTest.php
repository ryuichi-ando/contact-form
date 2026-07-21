<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Tag;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function お問い合わせフォーム入力ページが正常に表示される(): void
    {
        $category = Category::factory()->create([
            'content' => '商品のお届けについて',
        ]);

        $tag = Tag::factory()->create([
            'name' => 'Laravel',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertViewHas('categories');
        $response->assertViewHas('tags');

        $response->assertSee('商品のお届けについて');
        $response->assertSee('Laravel');
    }


    /** @test */
    public function サンクスページが正常に表示される(): void
    {
        $response = $this->get(route('contact.thanks'));

        $response->assertStatus(200);
    }
}

