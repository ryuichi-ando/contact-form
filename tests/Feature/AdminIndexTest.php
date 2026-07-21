<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Contact;
use App\Models\User;

class AdminIndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }


    /** @test */
    public function キーワード検索ができる(): void
    {
        $category = Category::factory()->create();

        Contact::factory()->create([
            'first_name' => '太郎',
            'last_name' => '山田',
            'email' => 'taro@example.com',
            'category_id' => $category->id,
        ]);

        Contact::factory()->create([
            'first_name' => '花子',
            'last_name' => '佐藤',
            'email' => 'hanako@example.com',
            'category_id' => $category->id,
        ]);

        $response = $this->get(route('admin.index', [
            'keyword' => '山田',
        ]));

        $response->assertStatus(200);

        $response->assertSee('山田');

        $response->assertDontSee('佐藤');
    }


    /** @test */
    public function 性別検索ができる(): void
    {
        $category = Category::factory()->create();

        Contact::factory()->create([
            'gender' => 1,
            'category_id' => $category->id,
        ]);

        Contact::factory()->create([
            'gender' => 2,
            'category_id' => $category->id,
        ]);

        $response = $this->get(route('admin.index', [
            'gender' => 1,
        ]));

        $response->assertStatus(200);

        $response->assertViewHas('contacts', function ($contacts) {
            return $contacts->every(fn($contact) => $contact->gender == 1);
        });
    }


    /** @test */
    public function カテゴリ検索ができる(): void
    {
        $category1 = Category::factory()->create([
            'content' => '商品',
        ]);

        $category2 = Category::factory()->create([
            'content' => 'その他',
        ]);

        Contact::factory()->create([
            'category_id' => $category1->id,
        ]);

        Contact::factory()->create([
            'category_id' => $category2->id,
        ]);

        $response = $this->get(route('admin.index', [
            'category_id' => $category1->id,
        ]));

        $response->assertStatus(200);

        $response->assertViewHas('contacts', function ($contacts) use ($category1) {
            return $contacts->every(fn($contact) => $contact->category_id == $category1->id);
        });
    }


    /** @test */
    public function 日付検索ができる(): void
    {
        $category = Category::factory()->create();

        Contact::factory()->create([
            'category_id' => $category->id,
            'created_at' => '2026-07-20',
        ]);

        Contact::factory()->create([
            'category_id' => $category->id,
            'created_at' => '2026-07-19',
        ]);

        $response = $this->get(route('admin.index', [
            'date' => '2026-07-20',
        ]));

        $response->assertStatus(200);

        $response->assertViewHas('contacts', function ($contacts) {
            return $contacts->every(function ($contact) {
                return $contact->created_at->format('Y-m-d') === '2026-07-20';
            });
        });
    }


    /** @test */
    public function 一覧は7件ずつページネーションされる(): void
    {
        $category = Category::factory()->create();

        Contact::factory()->count(10)->create([
            'category_id' => $category->id,
        ]);

        $response = $this->get(route('admin.index'));

        $response->assertStatus(200);

        $contacts = $response->viewData('contacts');

        $this->assertEquals(7, $contacts->count());

        $this->assertEquals(10, $contacts->total());
    }
}
