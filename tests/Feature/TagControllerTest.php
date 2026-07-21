<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function 認証済みユーザーはタグ編集画面を表示できる(): void
    {
        $user = User::factory()->create();
        $tag = Tag::create([
            'name' => 'PHP',
        ]);

        $response = $this->actingAs($user)
            ->get(route('admin.tags.edit', $tag));

        $response->assertStatus(200);
        $response->assertViewIs('admin.tags.edit');
        $response->assertViewHas('tag');
    }

    /** @test */
    public function 認証済みユーザーはタグを作成できる(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('admin.tags.store'), [
                'name' => 'Laravel',
            ]);

        $response->assertRedirect(route('admin.index'));

        $this->assertDatabaseHas('tags', [
            'name' => 'Laravel',
        ]);
    }

    /** @test */
    public function 認証済みユーザーはタグを更新できる(): void
    {
        $user = User::factory()->create();

        $tag = Tag::create([
            'name' => 'PHP',
        ]);

        $response = $this->actingAs($user)
            ->put(route('admin.tags.update', $tag), [
                'name' => 'Laravel',
            ]);

        $response->assertRedirect(route('admin.index'));

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => 'Laravel',
        ]);
    }

    /** @test */
    public function 認証済みユーザーはタグを削除できる(): void
    {
        $user = User::factory()->create();

        $tag = Tag::create([
            'name' => 'PHP',
        ]);

        $response = $this->actingAs($user)
            ->delete(route('admin.tags.destroy', $tag));

        $response->assertRedirect(route('admin.index'));

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }

    /** @test */
    public function 未認証ユーザーはタグ編集画面へアクセスできない(): void
    {
        $tag = Tag::create([
            'name' => 'PHP',
        ]);

        $response = $this->get(route('admin.tags.edit', $tag));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function 未認証ユーザーはタグを作成できない(): void
    {
        $response = $this->post(route('admin.tags.store'), [
            'name' => 'Laravel',
        ]);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function 未認証ユーザーはタグを更新できない(): void
    {
        $tag = Tag::create([
            'name' => 'PHP',
        ]);

        $response = $this->put(route('admin.tags.update', $tag), [
            'name' => 'Laravel',
        ]);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function 未認証ユーザーはタグを削除できない(): void
    {
        $tag = Tag::create([
            'name' => 'PHP',
        ]);

        $response = $this->delete(route('admin.tags.destroy', $tag));

        $response->assertRedirect(route('login'));
    }
}
