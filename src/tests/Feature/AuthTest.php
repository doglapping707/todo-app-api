<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
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
     * アカウント登録ができる
     */
    public function test_register(): void
    {
        $this->params['email'] = 'yamada@example.com';
        $params = array_merge($this->params, ['name' => 'yamada']);
        $res = $this->postJson('api/register', $params);
        $res->assertOk();
    }

    /**
     * 異常系
     * ユーザー名が空の場合はアカウント登録できない
     */
    public function test_register_required_name(): void
    {
        $this->params['email'] = 'yamada@example.com';
        $params = array_merge($this->params, ['name' => '']);
        $res = $this->postJson('api/register', $params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'ユーザー名が入力されていません。'
            ]);
    }

    /**
     * 異常系
     * ユーザー名が文字数制限を超えた場合はアカウント登録できない
     */
    public function test_register_limit_length_name(): void
    {
        $this->params['email'] = 'yamada@example.com';
        $params = array_merge($this->params, ['name' => Str::random(41)]);
        $res = $this->postJson('api/register', $params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'ユーザー名が40文字を超えています。'
            ]);
    }

    /**
     * 異常系
     * メールアドレスが空の場合はアカウント登録できない
     */
    public function test_register_required_email(): void
    {
        $this->params['email'] = '';
        $params = array_merge($this->params, ['name' => 'yamada']);
        $res = $this->postJson('api/register', $params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'メールアドレスが入力されていません。'
            ]);
    }

    /**
     * 異常系
     * メールアドレスが文字数制限を超えた場合はアカウント登録できない
     */
    public function test_register_limit_length_email(): void
    {
        $this->params['email'] = Str::random(244) . '@example.com';
        $params = array_merge($this->params, ['name' => 'yamada']);
        $res = $this->postJson('api/register', $params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'メールアドレスが255文字を超えています。'
            ]);
    }

    /**
     * 異常系
     * メールアドレスがユニークではない場合はアカウント登録できない
     */
    public function test_register_exist_email(): void
    {
        $params = array_merge($this->params, ['name' => 'yamada']);
        $res = $this->postJson('api/register', $params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'このメールアドレスは既に使われています。'
            ]);
    }

    /**
     * 異常系
     * メールアドレスが正しい形式ではない場合はアカウント登録できない
     */
    public function test_register_incorrect_email(): void
    {
        $this->params['email'] = 'admin';
        $params = array_merge($this->params, ['name' => 'yamada']);
        $res = $this->postJson('api/register', $params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'メールアドレスの形式が正しくありません。'
            ]);
    }

    /**
     * 異常系
     * パスワードが空の場合はアカウント登録できない
     */
    public function test_register_required_password(): void
    {
        $this->params['email'] = 'yamada@example.com';
        $this->params['password'] = '';
        $params = array_merge($this->params, ['name' => 'yamada']);
        $res = $this->postJson('api/register', $params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'パスワードが入力されていません。'
            ]);
    }

    /**
     * 異常系
     * パスワードが文字数制限を超えた場合はアカウント登録できない
     */
    public function test_register_limit_length_password(): void
    {
        $this->params['email'] = 'yamada@example.com';
        $this->params['password'] = Str::random(256);
        $params = array_merge($this->params, ['name' => 'yamada']);
        $res = $this->postJson('api/register', $params);
        $res->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'パスワードが255文字を超えています。'
            ]);
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
     * メールアドレスが空の場合はログインできない
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
     * メールアドレスの形式が正しくない場合はログインできない
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
     * パスワードが空の場合はログインできない
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

    /**
     * 正常系
     * ログアウトできる
     */
    public function test_logout(): void
    {
        $res = $this->withHeaders($this->headers)->postJson('api/logout');
        $res->assertOk();
    }
}
