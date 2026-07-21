<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class ConfirmContactTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function 正しい入力なら確認画面が表示される(): void
    {
        $category = Category::factory()->create([
            'content' => '商品のお届けについて',
        ]);

        $response = $this->post(route('contact.confirm'), [
            'first_name' => '太郎',
            'last_name' => '山田',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都新宿区',
            'building' => 'テストビル',
            'category_id' => $category->id,
            'detail' => 'お問い合わせ内容です',
        ]);

        $response->assertStatus(200);

        $response->assertViewIs('contact.confirm');

        $response->assertSee('山田');
        $response->assertSee('太郎');
        $response->assertSee('test@example.com');
        $response->assertSee('09012345678');
        $response->assertSee('東京都新宿区');
        $response->assertSee('テストビル');
        $response->assertSee('商品のお届けについて');
        $response->assertSee('お問い合わせ内容です');
    }


    /** @test */
    public function バリデーションエラーならリダイレクトされる(): void
    {
        $response = $this->from('/')
            ->post(route('contact.confirm'), [
                'first_name' => '',
                'last_name' => '',
                'gender' => '',
                'email' => '',
                'tel' => '',
                'address' => '',
                'category_id' => '',
                'detail' => '',
            ]);

        $response->assertRedirect('/');

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
}
