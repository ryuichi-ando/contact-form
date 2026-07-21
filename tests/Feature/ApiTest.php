<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use App\Models\User;

class ApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    /** @test */
    public function お問い合わせ一覧を取得できる(): void
    {
        Category::factory()->create();
        Contact::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/contacts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                    ]
                ],
                'links',
                'meta'
            ]);
    }


    /** @test */
    public function test_お問い合わせ詳細を取得できる(): void
    {
        Category::factory()->create();

        $contact = Contact::factory()->create();


        $response = $this->getJson(
            "/api/v1/contacts/{$contact->id}"
        );


        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'tel',
                    'address',
                    'detail',
                ]
            ]);
    }


    /** @test */
    public function test_存在しないお問い合わせIDでは404になる(): void
    {
        $response = $this->getJson(
            '/api/v1/contacts/99999'
        );

        $response->assertStatus(404);
    }

    /** @test */
    public function test_お問い合わせを登録できる(): void
    {
        $category = Category::factory()->create();


        $data = [
            'category_id' => $category->id,
            'first_name' => '太郎',
            'last_name' => '山田',
            'gender' => 1,
            'email' => 'taro@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'building' => 'マンション101',
            'detail' => 'お問い合わせ内容です',
        ];


        $response = $this->postJson(
            '/api/v1/contacts',
            $data
        );


        $response->assertStatus(201);


        $this->assertDatabaseHas('contacts', [
            'email' => 'taro@example.com',
            'first_name' => '太郎',
        ]);
    }


    /** @test */
    public function test_お問い合わせを更新できる(): void
    {
        $category = Category::factory()->create();

        $contact = Contact::factory()->create([
            'category_id' => $category->id,
            'first_name' => '太郎',
        ]);


        $data = [
            'category_id' => $category->id,
            'first_name' => '次郎',
            'last_name' => '山田',
            'gender' => 1,
            'email' => 'jiro@example.com',
            'tel' => '09011112222',
            'address' => '大阪府',
            'building' => 'マンション202',
            'detail' => '更新しました',
        ];


        $response = $this->putJson(
            "/api/v1/contacts/{$contact->id}",
            $data
        );


        $response->assertStatus(200);


        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => '次郎',
            'email' => 'jiro@example.com',
        ]);
    }


    /** @test */
    public function test_存在しないお問い合わせIDは更新できない(): void
    {
        $data = [
            'first_name' => '太郎',
            'last_name' => '山田',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都',
            'detail' => '内容',
        ];


        $response = $this->putJson(
            '/api/v1/contacts/99999',
            $data
        );


        $response->assertStatus(404);
    }


    /** @test */
    public function test_お問い合わせ更新時にバリデーションエラーになる(): void
    {
        $category = Category::factory()->create();

        $contact = Contact::factory()->create([
            'category_id' => $category->id,
        ]);


        $response = $this->putJson(
            "/api/v1/contacts/{$contact->id}",
            [
                'first_name' => '',
                'email' => 'invalid-email',
            ]
        );


        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'first_name',
                'email',
            ]);
    }


    /** @test */
    public function test_お問い合わせを削除できる(): void
    {
        $category = Category::factory()->create();

        $contact = Contact::factory()->create([
            'category_id' => $category->id,
        ]);


        $response = $this->deleteJson(
            "/api/v1/contacts/{$contact->id}"
        );


        $response->assertStatus(204);


        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id,
        ]);
    }


    /** @test */
    public function test_存在しないお問い合わせIDは削除できない(): void
    {
        $response = $this->deleteJson(
            '/api/v1/contacts/99999'
        );


        $response->assertStatus(404);
    }
}
