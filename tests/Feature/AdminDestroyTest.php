<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Contact;
use App\Models\User;

class AdminDestroyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function 管理者はお問い合わせを削除できる(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $category = Category::factory()->create();

        $contact = Contact::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->delete(route('admin.destroy', $contact));

        // /adminへリダイレクトされる
        $response->assertRedirect(route('admin.index'));

        // contactsテーブルから削除されている
        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id,
        ]);
    }
}
