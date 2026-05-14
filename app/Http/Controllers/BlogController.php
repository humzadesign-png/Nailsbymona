<?php

namespace App\Http\Controllers;

use App\Enums\BlogCategory;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('author')
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->get();

        $featured  = $posts->first();
        $gridPosts = $posts->skip(1)->values();

        return view('blog', compact('featured', 'gridPosts'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::with(['author', 'products'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $post->increment('view_count');

        $faqs = Faq::where('is_active', true)
            ->where('category', $post->category->value)
            ->orderBy('sort_order')
            ->get();

        // General FAQs as fallback if none for this category
        if ($faqs->isEmpty()) {
            $faqs = Faq::where('is_active', true)
                ->where('category', 'general')
                ->orderBy('sort_order')
                ->limit(6)
                ->get();
        }

        $relatedPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('category', $post->category->value)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $readingMinutes = max(1, (int) ceil(str_word_count(strip_tags($post->content ?? '')) / 200));

        return view('blog-post', compact('post', 'faqs', 'relatedPosts', 'readingMinutes'));
    }

    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        Subscriber::firstOrCreate(
            ['email' => $request->email],
            ['source' => 'blog_index', 'subscribed_at' => now()]
        );

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('subscribed', true);
    }
}
