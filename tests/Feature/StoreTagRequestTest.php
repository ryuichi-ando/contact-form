<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tag;

class StoreTagRequestTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    /** @test */
    public function 正しいタグ名なら登録できる(): void
    {
        $response = $this->post(route('admin.tags.store'), [
            'name' => 'Laravel',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tags', [
            'name' => 'Laravel',
        ]);
    }

    /** @test */
    public function タグ名が未入力ならエラーになる(): void
    {
        $response = $this->post(route('admin.tags.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function 重複したタグ名は登録できない(): void
    {
        Tag::create([
            'name' => 'PHP',
        ]);

        $response = $this->post(route('admin.tags.store'), [
            'name' => 'PHP',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
