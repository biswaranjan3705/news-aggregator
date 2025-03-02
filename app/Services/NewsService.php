<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Article;
use Illuminate\Support\Str;

class NewsService
{
    public function fetchNews()
    {
        $newsSources = [
            [
                'url' => 'https://newsapi.org/v2/top-headlines?country=us&apiKey=b41ca99fe4584a6583b3173886a3a96d',
                'source' => 'NewsAPI',
                'format' => 'newsapi'
            ],
            [
                'url' => 'https://content.guardianapis.com/search?api-key=411d18ed-2f32-4958-9ecb-553712011c02',
                'source' => 'The Guardian',
                'format' => 'guardian'
            ],
            [
                'url' => 'https://newsdata.io/api/1/latest?apikey=pub_7256085895b3aada676f774904112c315f856',
                'source' => 'NewsData',
                'format' => 'newsdata'
            ],
        ];

        foreach ($newsSources as $source) {
            $response = Http::get($source['url']);
            $data = $response->json();

            if (!$response->successful() || empty($data)) {
                continue;
            }

            switch ($source['format']) {
                case 'newsapi':
                    $articles = $data['articles'] ?? [];
                    foreach ($articles as $article) {
                        $this->storeArticle([
                            'title' => $article['title'] ?? 'No Title',
                            'description' => $article['description'] ?? '',
                            'author' => $article['author'] ?? 'Unknown',
                            'category' => $article['source']['name'] ?? 'General',
                            'url' => $article['url'] ?? '',
                            'image_url' => $article['urlToImage'] ?? '',
                            'published_at' => isset($article['publishedAt'])
    ? date('Y-m-d H:i:s', strtotime($article['publishedAt']))
    : now(),

                        ], $source['source']);
                    }
                    break;

                case 'guardian':
                    $articles = $data['response']['results'] ?? [];
                    foreach ($articles as $article) {
                        $this->storeArticle([
                            'title' => $article['webTitle'],
                            'description' => '',
                            'author' => $article['fields']['byline'] ?? 'Unknown',
                            'category' => 'General',
                            'url' => $article['webUrl'],
                            'image_url' => $article['fields']['thumbnail'] ?? '',
                            'published_at' => isset($article['webPublicationDate'])
                                            ? date('Y-m-d H:i:s', strtotime($article['webPublicationDate']))
                                            : now(),

                        ], $source['source']);
                    }
                    break;

                case 'newsdata':
                    $articles = $data['results'] ?? [];
                    foreach ($articles as $article) {
                        $this->storeArticle([
                            'title' => $article['title'],
                            'description' => $article['description'] ?? '',
                            'author' => $article['creator'][0] ?? 'Unknown',
                            'category' => $article['category'][0] ?? 'General',
                            'url' => $article['link'],
                            'image_url' => $article['image_url'] ?? '',
                            'published_at' => isset($article['pubDate'])
                                            ? date('Y-m-d H:i:s', strtotime($article['pubDate']))
                                            : now(),
                        ], $source['source']);
                    }
                    break;
            }
        }
    }

    private function storeArticle($article, $source)
    {
        Article::updateOrCreate(
            [
                'title' => $article['title'],
                'url' => $article['url'],
                'slug' => Str::slug($article['title']),
            ],
            [
                'content' => $article['description'] ?? '',
                'author' => $article['author'] ?? 'Unknown',
                'source' => $source,
                'category' => $article['category'] ?? 'General',
                'image_url' => $article['image_url'] ?? '',
                'published_at' => $article['publishedAt'] ?? now(),
            ]
        );
    }
}
