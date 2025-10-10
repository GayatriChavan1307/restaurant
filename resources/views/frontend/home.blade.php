@extends('layouts.frontend')

@section('title', 'Home - Global Import Export Solutions')
@section('meta_description', 'Leading import export company specializing in agricultural products, spices, and food items. Quality products, competitive prices, global shipping worldwide.')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="display-4 fw-bold mb-4">
                    Global Trade <span style="color: var(--accent-color);">Solutions</span> for Your Business
                </h1>
                <p class="lead mb-4">
                    We are a leading import-export company specializing in high-quality agricultural products, spices, and food items. Connect with reliable suppliers and expand your business globally.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-box me-2"></i>View Products
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Get Quote
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1586953208448-b95a79798f07?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Global Trade" class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient rounded-3" style="background: linear-gradient(45deg, rgba(44, 85, 48, 0.1), rgba(243, 156, 18, 0.1));"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Why Choose Us</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    We provide comprehensive import-export solutions with a focus on quality, reliability, and customer satisfaction.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-shield-alt fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Quality Assurance</h4>
                    <p class="text-muted">
                        Rigorous quality control processes ensure that all our products meet international standards and certifications.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-globe fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Global Network</h4>
                    <p class="text-muted">
                        Extensive network of suppliers and distributors across 50+ countries for seamless international trade.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-shipping-fast fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Fast Delivery</h4>
                    <p class="text-muted">
                        Efficient logistics and shipping solutions to ensure timely delivery of your orders worldwide.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-handshake fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Trusted Partner</h4>
                    <p class="text-muted">
                        Over 15 years of experience in international trade with thousands of satisfied customers.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-dollar-sign fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Competitive Prices</h4>
                    <p class="text-muted">
                        Direct sourcing from manufacturers ensures the best prices without compromising on quality.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-headset fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h4 class="fw-bold mb-3">24/7 Support</h4>
                    <p class="text-muted">
                        Round-the-clock customer support to assist you with all your trade requirements and queries.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Featured Products</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Discover our premium selection of high-quality products sourced from trusted suppliers worldwide.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card h-100">
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary mb-2 align-self-start">{{ $product->category->name }}</span>
                        <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->short_description, 100) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @if($product->price)
                                <span class="h6 mb-0 text-primary fw-bold">{{ $product->formatted_price }}</span>
                            @endif
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                View All Products <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="section-padding bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Product Categories</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Explore our diverse range of product categories to find exactly what you need for your business.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card h-100 text-center p-4">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="mx-auto mb-3 rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="mx-auto mb-3 bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-box text-white fa-2x"></i>
                        </div>
                    @endif
                    
                    <h4 class="fw-bold mb-3">{{ $category->name }}</h4>
                    <p class="text-muted mb-3">{{ Str::limit($category->description, 100) }}</p>
                    <p class="text-primary fw-bold mb-3">{{ $category->products_count }} Products</p>
                    
                    <a href="{{ route('products.category', $category) }}" class="btn btn-outline-primary">
                        Explore Category
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Statistics Section -->
<section class="section-padding" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="mb-3">
                    <i class="fas fa-globe-americas fa-3x"></i>
                </div>
                <h3 class="display-4 fw-bold mb-2">50+</h3>
                <p class="lead">Countries Served</p>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="mb-3">
                    <i class="fas fa-box fa-3x"></i>
                </div>
                <h3 class="display-4 fw-bold mb-2">1000+</h3>
                <p class="lead">Products Available</p>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="mb-3">
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <h3 class="display-4 fw-bold mb-2">5000+</h3>
                <p class="lead">Happy Customers</p>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="mb-3">
                    <i class="fas fa-calendar-alt fa-3x"></i>
                </div>
                <h3 class="display-4 fw-bold mb-2">15+</h3>
                <p class="lead">Years Experience</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
@if($testimonials->count() > 0)
<section class="section-padding bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">What Our Clients Say</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Don't just take our word for it. Here's what our satisfied customers have to say about our services.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($testimonials->take(3) as $testimonial)
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card h-100 p-4">
                    <div class="text-center mb-4">
                        @if($testimonial->image)
                            <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->customer_name }}" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        @endif
                        
                        <div class="text-warning mb-2">
                            {!! $testimonial->stars !!}
                        </div>
                    </div>
                    
                    <blockquote class="blockquote text-center">
                        <p class="mb-3">"{{ $testimonial->message }}"</p>
                        <footer class="blockquote-footer">
                            <strong>{{ $testimonial->customer_name }}</strong>
                            @if($testimonial->company_name)
                                <br><small>{{ $testimonial->company_name }}</small>
                            @endif
                            @if($testimonial->country)
                                <br><small>{{ $testimonial->country }}</small>
                            @endif
                        </footer>
                    </blockquote>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-3">Ready to Start Trading?</h2>
                <p class="lead text-muted mb-4">
                    Join thousands of satisfied customers who trust us for their import-export needs. Get started today and expand your business globally.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                <a href="{{ route('contact') }}" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-phone me-2"></i>Contact Us
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-search me-2"></i>Browse Products
                </a>
            </div>
        </div>
    </div>
</section>
@endsection