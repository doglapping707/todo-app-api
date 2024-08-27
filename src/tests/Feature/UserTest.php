<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public $headers;
    public $params;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');

        $this->headers = [
            'Accept' => 'application/json',
            'Origin' => 'http://localhost:5173'
        ];
        $this->params = [
            'email' => 'admin@example.com',
            'password' => 'Asdf1234'
        ];
        User::factory()->create($this->params);
    }

    protected function tearDown(): void
    {
        User::truncate();
    }

    /**
     * 正常系
     * ログインできる
     */
    public function test_login(): void
    {
        $res = $this->withHeaders($this->headers)->postJson('api/login', $this->params);
        $res->assertOk();
    }

    /**
     * 異常系
     * emailが空の場合はエラーが返却される
     */
    public function test_login_required_email(): void
    {
        $this->params['email'] = '';
        $res = $this->withHeaders($this->headers)->postJson('api/login', $this->params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'メールアドレスが入力されていません。'
            ]);
    }

    /**
     * 異常系
     * emailの形式が正しくないの場合はエラーが返却される
     */
    public function test_login_invalid_email(): void
    {
        $this->params['email'] = 'admin';
        $res = $this->withHeaders($this->headers)->postJson('api/login', $this->params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'メールアドレスの形式が正しくありません。'
            ]);
    }

    /**
     * 異常系
     * passwordが空の場合はエラーが返却される
     */
    public function test_login_required_password(): void
    {
        $this->params['password'] = '';
        $res = $this->withHeaders($this->headers)->postJson('api/login', $this->params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'パスワードが入力されていません。'
            ]);
    }

    /**
     * 異常系
     * 入力情報が正しくない場合はログインできない
     */
    public function test_login_Unauthenticated(): void
    {
        $this->params['password'] = 'Asdf1235';
        $res = $this->withHeaders($this->headers)->postJson('api/login', $this->params);
        $res->assertStatus(401);
    }
}
