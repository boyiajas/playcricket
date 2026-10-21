<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cricket Coaching & Training Blog | PlayCricket South Africa</title>
    <meta name="description" content="Expert cricket coaching guides, batting drills, bowling techniques, and youth academy training insights from PlayCricket South Africa.">
    <link rel="canonical" href="{{ url('/blog') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="edward-site-verification" content="{{ config('services.edward.site_id', env('EDWARD_SITE_ID', '2563d695-bf11-43cd-861c-13ce2cd6b015')) }}">
    <script defer src="https://cdn.meetedward.com/edward.js" data-site="{{ config('services.edward.site_id', env('EDWARD_SITE_ID', '2563d695-bf11-43cd-861c-13ce2cd6b015')) }}"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
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
                <a href="{{ url('/blog') }}" class="text-sm font-bold text-emerald-600">Blog</a>
                <a href="{{ url('/') }}#booking" class="bg-[#FF0F20] hover:bg-[#E60D1D] text-white text-xs sm:text-sm font-bold px-5 py-2.5 rounded-xl shadow-md transition-all">
                    Book a Lane (R350)
                </a>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="bg-gradient-to-b from-slate-900 to-slate-800 text-white py-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <span class="px-3.5 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-4 inline-block border border-emerald-500/30">
                Cricket Insights & Guides
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight mt-2 mb-4">
                PlayCricket Journal
            </h1>
            <p class="text-slate-300 text-base sm:text-lg max-w-2xl mx-auto">
                Master your batting, pace bowling, spin variations, and fitness with actionable guides and training programs written by professional coaches.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1">
        @if($categories->count() > 0)
            <div class="flex flex-wrap gap-2 mb-10 pb-4 border-b border-slate-200">
                <a href="{{ url('/blog') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($category) ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    All Articles
                </a>
                @foreach($categories as $cat)
                    <a href="{{ url('/blog?category=' . urlencode($cat)) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $category === $cat ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($articles->count() === 0)
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 max-w-lg mx-auto shadow-sm">
                <span class="text-5xl block mb-4">🏏</span>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Publishing In Progress</h3>
                <p class="text-sm text-slate-500 mb-6">Edward AI SEO is researching keywords and scheduling fresh articles for PlayCricket. Connected and ready for sync.</p>
                <a href="{{ url('/') }}" class="inline-block bg-slate-900 text-white text-xs font-bold px-6 py-3 rounded-xl hover:bg-slate-800 transition-all">
                    Back to PlayCricket Home
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles as $item)
                    <article class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col group">
                        <div class="h-48 overflow-hidden bg-slate-100 relative">
                            <img src="{{ $item->featured_image ?: '/images/private-coaching.jpg' }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md shadow-sm">
                                {{ $item->category }}
                            </span>
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="text-[11px] text-slate-400 font-medium mb-2 flex items-center gap-2">
                                    <span>{{ $item->published_at ? $item->published_at->format('M d, Y') : now()->format('M d, Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $item->reading_time }} min read</span>
                                </div>
                                <h2 class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-2 mb-2">
                                    <a href="{{ url('/blog/' . $item->slug) }}">
                                        {{ $item->title }}
                                    </a>
                                </h2>
                                <p class="text-xs text-slate-500 line-clamp-3 mb-4 leading-relaxed">
                                    {{ $item->excerpt }}
                                </p>
                            </div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-600 group-hover:underline">Read Guide →</span>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $item->author }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        @endif
    </main>

    <!-- Pricing CTA Strip -->
    <section class="bg-white border-t border-slate-200 py-12 px-4">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-2xl mb-1 block">🏏</span>
                <h4 class="font-bold text-slate-900 text-sm">Indoor Lane Hire</h4>
                <p class="text-xs text-slate-500 mt-1 mb-3">Immaculate pitch lanes & bowling machine access</p>
                <p class="text-xl font-extrabold text-emerald-600">R350 <span class="text-xs font-normal text-slate-500">/ hour</span></p>
            </div>
            <div class="p-6 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                <span class="text-2xl mb-1 block">🧢</span>
                <h4 class="font-bold text-slate-900 text-sm">Junior Coaching</h4>
                <p class="text-xs text-slate-500 mt-1 mb-3">Foundational technique & game awareness (Under 13)</p>
                <p class="text-xl font-extrabold text-emerald-700">R450 <span class="text-xs font-normal text-slate-500">/ hour</span></p>
            </div>
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-2xl mb-1 block">🏆</span>
                <h4 class="font-bold text-slate-900 text-sm">Senior Coaching</h4>
                <p class="text-xs text-slate-500 mt-1 mb-3">High-performance private 1-on-1 coaching (Above 13)</p>
                <p class="text-xl font-extrabold text-purple-600">R600 <span class="text-xs font-normal text-slate-500">/ hour</span></p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-8 border-t border-slate-800 text-center text-xs text-slate-400">
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
