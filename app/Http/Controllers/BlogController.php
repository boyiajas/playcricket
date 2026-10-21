<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Article::where('status', 'published')->orderBy('published_at', 'desc');

        if ($category) {
            $query->where('category', $category);
        }

        $articles = $query->paginate(9);
        $categories = Article::where('status', 'published')->distinct('category')->pluck('category');

        return view('blog.index', compact('articles', 'categories', 'category'));
    }

    /**
     * Display a single article.
     */
    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $related = Article::where('id', '!=', $article->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('blog.show', compact('article', 'related'));
    }

    /**
     * Generate dynamic XML sitemap for Google and AI search engines.
     */
    public function sitemap()
    {
        $articles = Article::where('status', 'published')->orderBy('updated_at', 'desc')->get();
        $baseUrl = rtrim(config('services.edward.site_url', 'https://playcricket.co.za'), '/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Core Pages
        $pages = [
            ['loc' => $baseUrl . '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $baseUrl . '/blog', 'priority' => '0.8', 'changefreq' => 'daily'],
        ];

        foreach ($pages as $page) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($page['loc']) . '</loc>' . PHP_EOL;
            $xml .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $page['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        // Article Pages
        foreach ($articles as $article) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($baseUrl . '/blog/' . $article->slug) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $article->updated_at->toAtomString() . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>weekly</changefreq>' . PHP_EOL;
            $xml .= '    <priority>0.7</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
