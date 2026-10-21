<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->meta_title ?: $article->title . ' | PlayCricket' }}</title>
    <meta name="description" content="{{ $article->meta_description ?: $article->excerpt }}">
    @if($article->focus_keyword)
        <meta name="keywords" content="{{ $article->focus_keyword }}, cricket coaching, south africa, playcricket">
    @endif
    <link rel="canonical" href="{{ url('/blog/' . $article->slug) }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url('/blog/' . $article->slug) }}">
    <meta property="og:title" content="{{ $article->meta_title ?: $article->title }}">
    <meta property="og:description" content="{{ $article->meta_description ?: $article->excerpt }}">
    <meta property="og:image" content="{{ $article->featured_image ? url($article->featured_image) : url('/images/private-coaching.jpg') }}">
    <meta property="og:site_name" content="PlayCricket South Africa">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $article->meta_title ?: $article->title }}">
    <meta name="twitter:description" content="{{ $article->meta_description ?: $article->excerpt }}">
    <meta name="twitter:image" content="{{ $article->featured_image ? url($article->featured_image) : url('/images/private-coaching.jpg') }}">

    <!-- Edward Verification & Script -->
    <meta name="edward-site-verification" content="{{ config('services.edward.site_id', env('EDWARD_SITE_ID', '2563d695-bf11-43cd-861c-13ce2cd6b015')) }}">
    <script defer src="https://cdn.meetedward.com/edward.js" data-site="{{ config('services.edward.site_id', env('EDWARD_SITE_ID', '2563d695-bf11-43cd-861c-13ce2cd6b015')) }}"></script>

    <!-- Structured Data JSON-LD -->
    <script type="application/ld+json">
    @php
      $schema = [
        chr(64) . 'context' => 'https://schema.org',
        chr(64) . 'type' => 'Article',
        'headline' => $article->title,
        'description' => $article->meta_description ?: $article->excerpt,
        'image' => $article->featured_image ? url($article->featured_image) : url('/images/private-coaching.jpg'),
        'datePublished' => $article->published_at ? $article->published_at->toIso8601String() : now()->toIso8601String(),
        'dateModified' => $article->updated_at->toIso8601String(),
        'author' => [
          chr(64) . 'type' => 'Organization',
          'name' => $article->author ?: 'PlayCricket Coaching Team',
          'url' => url('/'),
        ],
        'publisher' => [
          chr(64) . 'type' => 'Organization',
          'name' => 'PlayCricket',
          'logo' => [
            chr(64) . 'type' => 'ImageObject',
            'url' => url('/logo.png'),
          ],
        ],
        'mainEntityOfPage' => [
          chr(64) . 'type' => 'WebPage',
          chr(64) . 'id' => url('/blog/' . $article->slug),
        ],
      ];
    @endphp
    {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .article-body p { margin-bottom: 1.25rem; line-height: 1.75; color: #334155; }
        .article-body h2 { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-top: 2rem; margin-bottom: 1rem; }
        .article-body h3 { font-size: 1.25rem; font-weight: 600; color: #1e293b; margin-top: 1.5rem; margin-bottom: 0.75rem; }
        .article-body ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; color: #334155; }
        .article-body ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.25rem; color: #334155; }
        .article-body li { margin-bottom: 0.5rem; }
        .article-body blockquote { border-left: 4px solid #10b981; padding-left: 1rem; font-style: italic; color: #475569; margin: 1.5rem 0; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="/logo.png" alt="PlayCricket Logo" class="h-10 w-auto">
                <div>
                    <span class="font-extrabold text-xl text-slate-900 tracking-tight">PlayCricket</span>
                    <span class="block text-[10px] text-emerald-600 font-bold tracking-widest uppercase">Coaching & Lanes</span>
                </div>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ url('/blog') }}" class="text-sm font-bold text-slate-600 hover:text-emerald-600">All Articles</a>
                <a href="{{ url('/') }}#booking" class="bg-[#FF0F20] hover:bg-[#E60D1D] text-white text-xs sm:text-sm font-bold px-5 py-2.5 rounded-xl shadow-md transition-all">
                    Book a Lane (R350)
                </a>
            </div>
        </div>
    </header>

    <!-- Breadcrumbs -->
    <div class="bg-white border-b border-slate-100 py-3">
        <div class="max-w-4xl mx-auto px-4 text-xs text-slate-500 flex items-center gap-2">
            <a href="{{ url('/') }}" class="hover:text-emerald-600">Home</a>
            <span>/</span>
            <a href="{{ url('/blog') }}" class="hover:text-emerald-600">Blog</a>
            <span>/</span>
            <span class="text-slate-800 font-semibold truncate">{{ $article->title }}</span>
        </div>
    </div>

    <!-- Article Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-12 flex-1 w-full">
        <article class="bg-white rounded-3xl p-6 sm:p-12 border border-slate-200 shadow-sm">
            <div class="mb-8">
                <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-4">
                    {{ $article->category }}
                </span>
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                    {{ $article->title }}
                </h1>
                <div class="flex items-center gap-4 text-xs text-slate-500 pb-6 border-b border-slate-100">
                    <span class="font-medium text-slate-700">By {{ $article->author ?: 'PlayCricket Coaches' }}</span>
                    <span>•</span>
                    <span>{{ $article->published_at ? $article->published_at->format('F d, Y') : now()->format('F d, Y') }}</span>
                    <span>•</span>
                    <span>{{ $article->reading_time }} min read</span>
                </div>
            </div>

            @if($article->featured_image)
                <div class="mb-10 rounded-2xl overflow-hidden bg-slate-100 max-h-96">
                    <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="article-body text-base leading-relaxed">
                {!! $article->content !!}
            </div>

            <!-- Booking Call To Action Box -->
            <div class="mt-12 p-8 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 text-white shadow-xl">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <span class="text-emerald-400 font-extrabold uppercase tracking-widest text-xs">PlayCricket Facilities</span>
                        <h3 class="text-2xl font-bold mt-1">Ready to put this into practice?</h3>
                        <p class="text-slate-300 text-xs sm:text-sm mt-1 max-w-xl">
                            Book your lane hire or coaching session online in less than 2 minutes:
                        </p>
                        <div class="flex flex-wrap gap-3 mt-3 text-xs">
                            <span class="bg-white/10 px-3 py-1 rounded-lg">🏏 Lane Hire: <strong>R350/hr</strong></span>
                            <span class="bg-white/10 px-3 py-1 rounded-lg">🧢 Junior Coaching: <strong>R450/hr</strong></span>
                            <span class="bg-white/10 px-3 py-1 rounded-lg">🏆 Senior Coaching: <strong>R600/hr</strong></span>
                        </div>
                    </div>
                    <a href="{{ url('/') }}#booking" class="bg-[#FF0F20] hover:bg-[#E60D1D] text-white font-bold px-8 py-4 rounded-xl text-sm whitespace-nowrap shadow-lg transition-all hover:scale-105 active:scale-95">
                        Book Your Slot Now
                    </a>
                </div>
            </div>
        </article>

        @if($related->count() > 0)
            <div class="mt-16">
                <h3 class="text-xl font-bold text-slate-900 mb-6">Related Articles & Guides</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related as $rel)
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
                            <span class="text-[10px] font-bold text-emerald-600 uppercase">{{ $rel->category }}</span>
                            <h4 class="font-bold text-slate-800 text-sm mt-1 mb-2 line-clamp-2">
                                <a href="{{ url('/blog/' . $rel->slug) }}" class="hover:text-emerald-600">
                                    {{ $rel->title }}
                                </a>
                            </h4>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $rel->excerpt }}</p>
                            <a href="{{ url('/blog/' . $rel->slug) }}" class="text-xs font-bold text-emerald-600 hover:underline">Read →</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-8 border-t border-slate-800 text-center text-xs text-slate-400 mt-12">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p>© {{ date('Y') }} PlayCricket.co.za. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
                <a href="{{ url('/blog') }}" class="hover:text-white transition-colors">Blog</a>
                <a href="{{ url('/sitemap.xml') }}" class="hover:text-white transition-colors">Sitemap</a>
            </div>
        </div>
    </footer>
</body>
</html>
