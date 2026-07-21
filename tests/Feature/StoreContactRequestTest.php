<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Tag;

class StoreContactRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function 正しい入力なら保存できる()
    {
        $category = Category::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $response = $this->post(route('contact.store'), [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'yamada@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'building' => 'テストビル',
            'category_id' => $category->id,
            'detail' => 'テストお問い合わせ',
            'tag_ids' => $tags->pluck('id')->toArray(),
        ]);

        $response->assertRedirect(route('contact.thanks'));

        $this->assertDatabaseHas('contacts', [
            'email' => 'yamada@example.com',
        ]);
    }


    /** @test */
    public function 必須項目が未入力ならエラーになる()
    {
        $response = $this->post(route('contact.store'), []);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'gender',
            'email',
            'tel',
            'address',
            'category_id',
            'detail',
        ]);
    }


    /** @test */
    public function 電話番号形式が不正ならエラーになる()
    {
        $category = Category::factory()->create();

        $response = $this->post(route('contact.store'), [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'yamada@example.com',
            'tel' => '090-1234-5678',
            'address' => '東京都',
            'category_id' => $category->id,
            'detail' => 'テスト',
        ]);

        $response->assertSessionHasErrors('tel');
    }


    /** @test */
    public function 存在しないカテゴリーならエラーになる()
    {
        $response = $this->post(route('contact.store'), [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'yamada@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'category_id' => 999,
            'detail' => 'テスト',
        ]);

        $response->assertSessionHasErrors('category_id');
    }
}
