<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NewsArticleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_only_sees_published_articles(): void
    {
        NewsArticle::query()->create([
            'title' => 'Published news',
            'slug' => 'published-news',
            'content' => 'Public content.',
            'published_at' => now(),
        ]);

        NewsArticle::query()->create([
            'title' => 'Draft news',
            'slug' => 'draft-news',
            'content' => 'Draft content.',
        ]);

        $this->getJson('/api/news-articles')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'published-news');
    }

    public function test_admin_can_create_news_article(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $this->postJson('/api/news-articles', [
            'title' => 'Xu ly vi pham qua camera',
            'content' => 'Noi dung tin tuc.',
            'published_at' => now()->toDateTimeString(),
        ])->assertCreated()
            ->assertJsonPath('slug', 'xu-ly-vi-pham-qua-camera');
    }
}
