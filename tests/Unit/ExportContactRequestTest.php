<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\ExportContactRequest;
use Illuminate\Support\Facades\Validator;

class ExportContactRequestTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    /** @test */
    public function 正しい検索条件ならバリデーションを通過する(): void
    {
        $request = new ExportContactRequest();
        $validator = Validator::make(
            [
                'keyword' => '山田',
                'gender' => 1,
                'category_id' => 1,
                'date' => '2026-07-20',
            ],
            $request->rules()
        );
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function 存在しない性別はエラーになる(): void
    {
        $request = new ExportContactRequest();
        $validator = Validator::make(
            [
                'gender' => 5,
            ],
            $request->rules()
        );
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('gender', $validator->errors()->toArray());
    }

    /** @test */
    public function 存在しないカテゴリーIDはエラーとなる(): void
    {
        $request = new ExportContactRequest();
        $validator = Validator::make(
            [
                'category_id' => 999,
            ],
            $request->rules()
        );
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('category_id', $validator->errors()->toArray());
    }
}
