<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function testIndex()
    {
        $response = $this->get(route('articles.index'));

        $response->assertStatus(200)
            ->assertViewIs('articles.index');
    }
    
    public function testGuestCreate() //docker-compose exec workspace vendor/bin/phpunit --filter=guest
    {
        $response = $this->get(route('articles.create'));

        $response->assertRedirect(route('login'));
    }

    public function testAuthCreate()
    {
        // 1. テストに必要なUserモデルを「準備」
        //factory関数を使用することで、テストに必要なモデルのインスタンスを、ファクトリというものを利用して生成。
        $user = factory(User::class)->create(); //ファクトリによって生成されたUserモデルがデータベースに保存されます。

        // 2. ログインして記事投稿画面にアクセスすることを「実行」
        $response = $this->actingAs($user)
            ->get(route('articles.create')); //actingAsメソッドは、引数として渡したUserモデルにてログインした状態を作り出します。

        // 3. レスポンスを「検証」
        $response->assertStatus(400)
            ->assertViewIs('articles.create'); //記事投稿画面のビューが使用されているかをテストします。
    }
}
