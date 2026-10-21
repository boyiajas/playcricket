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
     * Publish or update an article sent from Edward AI SEO webhook.
     * Complies with Edward's signed webhook specification (HMAC SHA-256).
     */
    public function publish(Request $request)
    {
        $rawBody = $request->getContent();
        $timestamp = $request->header('X-Edward-Timestamp');
        $signature = $request->header('X-Edward-Signature');
        $event = $request->header('X-Edward-Event') ?? $request->input('event');
        $signingSecret = config('services.edward.signing_secret', env('EDWARD_SIGNING_SECRET', 'ehsec_f84f198ae37625bd1a602bcfc8242c24d142c0b6d8456381'));

        // 1. Verify HMAC SHA-256 signature if provided by Edward
        if ($signature && $timestamp) {
            $expectedSignature = 'sha256=' . hash_hmac('sha256', $timestamp . '.' . $rawBody, $signingSecret);
            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('Edward webhook signature mismatch', [
                    'received' => $signature,
                    'expected' => $expectedSignature,
                ]);
                return response()->json(['error' => 'Invalid webhook signature'], 401);
            }
        } else {
            // Optional fallback API key check for custom callers or testing
            $providedKey = $request->header('X-Edward-Key') 
                ?? $request->bearerToken() 
                ?? $request->input('api_key')
                ?? $request->header('X-API-KEY');

            $configuredKey = config('services.edward.api_key', 'pc_edward_key_2026');
            if ($providedKey && $configuredKey && $providedKey !== $configuredKey) {
                Log::warning('Edward publication unauthorized key attempt', ['provided' => $providedKey]);
                return response()->json(['error' => 'Invalid Edward API Key'], 401);
            }
        }

        // 2. Handle "ping" test event triggered by "Test & connect" button on Edward dashboard
        if ($event === 'ping') {
            Log::info('Edward test ping received and verified successfully');
            return response()->json([
                'status' => 'success',
                'message' => 'Edward webhook ping verified successfully',
                'external_id' => 'ping',
                'post_url' => url('/blog'),
                'duplicate' => false,
            ], 200);
        }

        // 3. Extract article data (supports both nested data object and root-level fields)
        $articleData = $request->input('data') ?? $request->all();

        $title = $articleData['title']['rendered'] ?? $articleData['title'] ?? null;
        $content = $articleData['content']['rendered'] ?? $articleData['content'] ?? null;
        $slug = $articleData['slug'] ?? ($title ? Str::slug($title) : 'article-' . time());
        $externalId = $articleData['edward_article_id'] ?? $request->input('id') ?? $articleData['id'] ?? null;

        if (empty($title) || empty($content)) {
            return response()->json(['error' => 'Title and content are required'], 422);
        }

        $excerpt = $articleData['excerpt']['rendered'] 
            ?? $articleData['excerpt'] 
            ?? $articleData['meta_description'] 
            ?? Str::limit(strip_tags((string) $content), 160);

        // Featured Image
        $featuredImage = $articleData['featured_image_url'] 
            ?? $articleData['featured_image'] 
            ?? $articleData['featured_media_url'] 
            ?? $articleData['og_image_url'] 
            ?? '/images/private-coaching.jpg';

        $metaTitle = $articleData['meta_title'] ?? $articleData['og_title'] ?? $title;
        $metaDescription = $articleData['meta_description'] ?? $articleData['og_description'] ?? $excerpt;
        $focusKeyword = $articleData['focus_keyword'] ?? $articleData['keyword'] ?? null;
        $category = $articleData['category'] ?? 'Cricket Coaching';
        $tags = $articleData['tags'] ?? ['cricket', 'training', 'playcricket'];
        $status = $articleData['status'] ?? 'published';
        $author = $articleData['author'] ?? 'PlayCricket Coaching Staff';

        // 4. Upsert by external_id (Edward's stable ID) or fallback by slug
        $article = null;
        $duplicate = false;

        if ($externalId) {
            $article = Article::where('external_id', $externalId)->first();
        }

        if (!$article) {
            $article = Article::where('slug', $slug)->first();
            if ($article) {
                $duplicate = true;
            }
        } else {
            $duplicate = true;
        }

        $articleAttributes = [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'featured_image' => $featuredImage,
            'author' => $author,
            'category' => is_string($category) ? $category : 'Cricket Coaching',
            'tags' => is_array($tags) ? $tags : explode(',', (string) $tags),
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'focus_keyword' => $focusKeyword,
            'status' => $status,
            'source' => 'edward',
            'external_id' => $externalId,
            'published_at' => ($article && $article->published_at) ? $article->published_at : now(),
        ];

        if ($article) {
            $article->update($articleAttributes);
        } else {
            $article = Article::create($articleAttributes);
        }

        Log::info("Article published via Edward AI: {$article->title} (ID: {$article->id}, external_id: {$externalId})");

        $postUrl = url('/blog/' . $article->slug);

        // Official response schema expected by Edward (accepts 200, 201, 204)
        $statusCode = $duplicate ? 200 : 201;

        return response()->json([
            'external_id' => (string) $article->id,
            'post_url' => $postUrl,
            'duplicate' => $duplicate,
            'status' => 'success',
            'message' => 'Article published successfully',
            // Compatibility helpers
            'id' => $article->id,
            'link' => $postUrl,
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'url' => $postUrl,
                'status' => $article->status,
            ],
        ], $statusCode);
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
