<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\CompanyProfile;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->active()
            ->featured()
            ->limit(8)
            ->get();

        $categories = Category::active()
            ->parent()
            ->withCount('products')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $testimonials = Testimonial::active()
            ->featured()
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $companyProfile = CompanyProfile::getProfile();

        $recentPosts = BlogPost::with('author')
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('frontend.home', compact(
            'featuredProducts',
            'categories',
            'testimonials',
            'companyProfile',
            'recentPosts'
        ));
    }

    public function about()
    {
        $companyProfile = CompanyProfile::getProfile();
        
        $testimonials = Testimonial::active()
            ->orderBy('sort_order')
            ->limit(10)
            ->get();

        $stats = [
            'years_experience' => now()->year - ($companyProfile->established_year ?? now()->year),
            'products_count' => Product::active()->count(),
            'countries_exported' => count($companyProfile->export_countries ?? []),
            'happy_customers' => Testimonial::active()->count()
        ];

        return view('frontend.about', compact('companyProfile', 'testimonials', 'stats'));
    }

    public function contact()
    {
        $companyProfile = CompanyProfile::getProfile();
        
        return view('frontend.contact', compact('companyProfile'));
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ]);

        // Here you can add logic to send email or store contact form data
        // For now, we'll just return a success message
        
        return back()->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}