<?php

namespace App\Http\Controllers;

use App\Enums\FaqCategory;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $products = Product::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        return view('shop', compact('products'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $related = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->limit(3)
            ->get();

        // FAQs for the product page. Pull active general FAQs (5-7); Mona
        // edits these in the FAQs admin resource. Until she fills the table,
        // the product page falls back to a sensible default set so the
        // section never renders empty.
        $faqs = Faq::where('is_active', true)
            ->where('category', FaqCategory::General->value)
            ->orderBy('sort_order')
            ->limit(7)
            ->get();

        return view('product', compact('product', 'related', 'faqs'));
    }
}
