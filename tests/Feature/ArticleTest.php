<?php

namespace Tests\Feature;

use App\Article;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    // docker-compose exec workspace vendor/bin/phpunit --filter=null
    // --filter=nullとオプションを指定したことで、条件に合致するtestIsLikedByNullのテストが実行されます
    use RefreshDatabase;

    public function testIsLikedByNull()
    {
        $article = factory(Article::class)->create();

        $result = $article->isLikedBy(null); //引数としてnullを渡し、その戻り値が変数$result

        $this->assertFalse($result); //引数がfalseかどうかをテスト
    }

    //テストの実行(いいねをしているケース)
    //docker-compose exec workspace vendor/bin/phpunit --filter=theuser
    public function testIsLikedByTheUser()
    {
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();
        $article->likes()->attach($user);

        $result = $article->isLikedBy($user);

        $this->assertTrue($result);
    }

    //テストの実行(いいねをしていないケース)
    //docker-compose exec workspace vendor/bin/phpunit --filter=another
    public function testIsLikedByAnother()
    {
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();
        $another = factory(User::class)->create();
        $article->likes()->attach($another);

        $result = $article->isLikedBy($user);

        $this->assertFalse($result);
    }
}
