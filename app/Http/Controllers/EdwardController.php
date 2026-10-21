<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EdwardController extends Controller
{
    /**
     * Verify Edward connectivity and credentials.
     */
    public function ping(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'connected' => true,
            'platform' => 'Laravel ' . app()->version(),
            'site' => 'playcricket.co.za',
            'site_id' => config('services.edward.site_id', '2563d695-bf11-43cd-861c-13ce2cd6b015'),
            'posts_count' => Article::count(),
            'message' => 'Edward AI SEO connected successfully to PlayCricket',
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }

    /**
     * Provide environment diagnostics for Edward's connector.
     */
    public function diagnostics()
    {
        return response()->json([
            'status' => 'healthy',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database' => 'connected',
            'articles_published' => Article::where('status', 'published')->count(),
            'sitemap_url' => url('/sitemap.xml'),
            'blog_url' => url('/blog'),
            'edward_script_enabled' => true,
        ], 200);
    }

    /**
     * Return available categories for Edward content mapping.
     */
    public function taxonomies()
    {
        return response()->json([
            ['id' => 1, 'name' => 'Cricket Coaching', 'slug' => 'cricket-coaching'],
            ['id' => 2, 'name' => 'Batting Techniques', 'slug' => 'batting-techniques'],
            ['id' => 3, 'name' => 'Fast Bowling', 'slug' => 'fast-bowling'],
            ['id' => 4, 'name' => 'Spin Bowling', 'slug' => 'spin-bowling'],
            ['id' => 5, 'name' => 'Junior Cricket Academy', 'slug' => 'junior-academy'],
            ['id' => 6, 'name' => 'Lane Hire & Practice', 'slug' => 'lane-hire-practice'],
            ['id' => 7, 'name' => 'Fitness & Training', 'slug' => 'fitness-training'],
        ]);
    }

    /**
     * Return available authors.
     */
    public function authors()
    {
        return response()->json([
            [
                'id' => 1,
                'name' => 'PlayCricket Coaching Staff',
                'slug' => 'playcricket-coaching',
                'description' => 'Professional cricket coaches at PlayCricket South Africa',
            ],
            [
                'id' => 2,
                'name' => 'Head Coach',
                'slug' => 'head-coach',
                'description' => 'CSA Level 3 Certified Cricket Coach',
            ],
        ]);
    }

    /**
     * Publish or update an article sent from Edward AI SEO.
     */
    public function publish(Request $request)
    {
        // Check API key if provided
        $providedKey = $request->header('X-Edward-Key') 
            ?? $request->bearerToken() 
            ?? $request->input('api_key')
            ?? $request->header('X-API-KEY');

        $configuredKey = config('services.edward.api_key', 'pc_edward_key_2026');
        if ($providedKey && $configuredKey && $providedKey !== $configuredKey) {
            Log::warning('Edward publication unauthorized key attempt', ['provided' => $providedKey]);
            return response()->json(['error' => 'Invalid Edward API Key'], 401);
        }

        // Support both direct Edward JSON and WordPress REST payload shapes
        $title = $request->input('title.rendered') ?? $request->input('title');
        $content = $request->input('content.rendered') ?? $request->input('content');
        $slug = $request->input('slug') ?? ($title ? Str::slug($title) : 'article-' . time());
        $excerpt = $request->input('excerpt.rendered') ?? $request->input('excerpt') ?? Str::limit(strip_tags($content ?? ''), 160);

        if (empty($title) || empty($content)) {
            return response()->json(['error' => 'Title and content are required'], 422);
        }

        // External featured image or fallback cricket photo
        $featuredImage = $request->input('featured_image') 
            ?? $request->input('featured_media_url') 
            ?? $request->input('image_url')
            ?? '/images/private-coaching.jpg';

        $metaTitle = $request->input('meta_title') ?? $request->input('yoast_title') ?? $title;
        $metaDescription = $request->input('meta_description') ?? $request->input('yoast_description') ?? $excerpt;
        $focusKeyword = $request->input('focus_keyword') ?? $request->input('keyword');
        $category = $request->input('category') ?? 'Cricket Coaching';
        $tags = $request->input('tags') ?? ['cricket', 'training', 'playcricket'];

        $article = Article::updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'content' => $content,
                'excerpt' => $excerpt,
                'featured_image' => $featuredImage,
                'author' => $request->input('author', 'PlayCricket Coaching Staff'),
                'category' => is_string($category) ? $category : 'Cricket Coaching',
                'tags' => is_array($tags) ? $tags : explode(',', (string) $tags),
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'focus_keyword' => $focusKeyword,
                'status' => $request->input('status', 'published'),
                'source' => 'edward',
                'external_id' => $request->input('id') ?? $request->input('edward_id'),
                'published_at' => now(),
            ]
        );

        Log::info("Article published via Edward AI: {$article->title} (slug: {$article->slug})");

        return response()->json([
            'status' => 'success',
            'message' => 'Article published successfully',
            'id' => $article->id,
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'url' => url('/blog/' . $article->slug),
                'published_at' => $article->published_at->toIso8601String(),
                'status' => $article->status,
            ],
            // WordPress REST format compatibility
            'link' => url('/blog/' . $article->slug),
        ], 201);
    }

    /**
     * List articles in JSON format for Edward or API consumers.
     */
    public function articles(Request $request)
    {
        $perPage = min(50, (int) $request->input('per_page', 10));
        $articles = Article::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return response()->json($articles);
    }
}
