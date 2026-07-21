<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Tag;

class StoreContactTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function 正しい入力ならお問い合わせを保存できる(): void
    {
        $category = Category::factory()->create();

        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        $response = $this->post(route('contact.store'), [
            'first_name' => '太郎',
            'last_name' => '山田',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'building' => 'テストビル',
            'category_id' => $category->id,
            'detail' => 'お問い合わせ内容',
            'tag_ids' => [
                $tag1->id,
                $tag2->id,
            ],
        ]);

        $response->assertRedirect(route('contact.thanks'));

        $this->assertDatabaseHas('contacts', [
            'first_name' => '太郎',
            'last_name' => '山田',
            'email' => 'test@example.com',
        ]);

        $contactId = \App\Models\Contact::first()->id;

        $this->assertDatabaseHas('contact_tag', [
            'contact_id' => $contactId,
            'tag_id' => $tag1->id,
        ]);

        $this->assertDatabaseHas('contact_tag', [
            'contact_id' => $contactId,
            'tag_id' => $tag2->id,
        ]);
    }


    /** @test */
    public function バリデーションエラーなら保存されない(): void
    {
        $response = $this->from('/')
            ->post(route('contact.store'), [
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

        $this->assertDatabaseCount('contacts', 0);
    }
}
