<?php

namespace Tests\Unit;
;
use App\Http\Requests\IndexContactRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IndexContactRequestTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    /** @test */
    public function 正しい検索条件ならバリデーションを通過する(): void
    {
        $request = new IndexContactRequest();
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
    public function 性別が不正ならエラーになる(): void
    {
        $request = new IndexContactRequest();

        $validator = Validator::make(
            [
                'gender' => 5,
            ],
            $request->rules()
        );

        $this->assertFalse($validator->passes());

        $this->assertArrayHasKey(
            'gender',
            $validator->errors()->toArray()
        );
    }


    /** @test */
    public function 存在しないカテゴリーならエラーになる()
    {
        $request = new IndexContactRequest();

        $validator = Validator::make(
            [
                'category_id' => 999,
            ],
            $request->rules()
        );

        $this->assertFalse($validator->passes());

        $this->assertArrayHasKey(
            'category_id',
            $validator->errors()->toArray()
        );
    }


    /** @test */
    public function 日付形式が不正ならエラーになる()
    {
        $request = new IndexContactRequest();

        $validator = Validator::make(
            [
                'date' => '2026-15-99',
            ],
            $request->rules()
        );

        $this->assertFalse($validator->passes());

        $this->assertArrayHasKey(
            'date',
            $validator->errors()->toArray()
        );
    }
}
