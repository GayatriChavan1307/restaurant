@extends('layouts.frontend')

@section('title', 'Products - Import Export Quality Products Worldwide')
@section('meta_description', 'Browse our extensive catalog of import export products including agricultural products, spices, food items, and industrial goods. Quality guaranteed, competitive prices.')

@section('content')
<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold mb-4" data-aos="fade-up">Our Products</h1>
                <p class="lead" data-aos="fade-up" data-aos-delay="100">
                    Discover our extensive range of high-quality products sourced from trusted suppliers worldwide
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Search -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <form method="GET" action="{{ route('products.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search products..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" 
                                        {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="country">
                            <option value="">All Countries</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}" 
                                        {{ request('country') == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end">
                    <span class="me-3">Sort by:</span>
                    <select class="form-select" style="width: auto;" onchange="window.location.href=this.value">
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'name', 'order' => 'asc'])) }}" 
                                {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'name', 'order' => 'desc'])) }}" 
                                {{ request('sort') == 'name' && request('order') == 'desc' ? 'selected' : '' }}>Name Z-A</option>
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'newest'])) }}" 
                                {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price', 'order' => 'asc'])) }}" 
                                {{ request('sort') == 'price' ? 'selected' : '' }}>Price Low-High</option>
                        <option value="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price', 'order' => 'desc'])) }}" 
                                {{ request('sort') == 'price' && request('order') == 'desc' ? 'selected' : '' }}>Price High-Low</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="section-padding">
    <div class="container">
        @if($products->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted">
                        Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products
                        @if(request('search'))
                            for "<strong>{{ request('search') }}</strong>"
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 6) * 100 }}">
                    <div class="card h-100 product-card">
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}" 
                                 class="card-img-top" alt="{{ $product->name }}" 
                                 style="height: 250px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 250px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                                @if($product->is_featured)
                                    <span class="badge bg-warning">Featured</span>
                                @endif
                            </div>
                            
                            <h5 class="card-title fw-bold">
                                <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                                    {{ $product->name }}
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted flex-grow-1">
                                {{ Str::limit($product->short_description, 120) }}
                            </p>
                            
                            <div class="product-details mb-3">
                                @if($product->origin_country)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Origin: {{ $product->origin_country }}
                                    </small>
                                @endif
                                
                                @if($product->minimum_order_quantity)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-box me-1"></i>
                                        MOQ: {{ number_format($product->minimum_order_quantity) }} {{ $product->unit }}
                                    </small>
                                @endif
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                @if($product->price)
                                    <span class="h6 mb-0 text-primary fw-bold">{{ $product->formatted_price }}</span>
                                @else
                                    <span class="text-muted">Price on request</span>
                                @endif
                                
                                <div class="btn-group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                    <button type="button" class="btn btn-primary btn-sm" 
                                            onclick="showInquiryModal('{{ $product->id }}', '{{ $product->name }}')">
                                        Inquire
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-4"></i>
                    <h3 class="fw-bold mb-3">No Products Found</h3>
                    <p class="text-muted mb-4">
                        We couldn't find any products matching your criteria. 
                        Try adjusting your search filters or browse all products.
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>View All Products
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="section-padding bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Browse by Category</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Explore our product categories to find exactly what you're looking for
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <a href="{{ route('products.category', $category) }}" class="text-decoration-none">
                    <div class="card h-100 text-center p-4 category-card">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 alt="{{ $category->name }}" class="mx-auto mb-3 rounded-circle" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="mx-auto mb-3 bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-box text-white fa-2x"></i>
                            </div>
                        @endif
                        
                        <h4 class="fw-bold mb-3 text-dark">{{ $category->name }}</h4>
                        <p class="text-muted mb-3">{{ Str::limit($category->description, 100) }}</p>
                        <p class="text-primary fw-bold mb-0">{{ $category->products_count }} Products</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Quick Inquiry Modal -->
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inquiryModalLabel">Product Inquiry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="inquiryForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="inquiry_product_id" name="product_id">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Inquiring about: <strong id="inquiry_product_name"></strong>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="customer_email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="customer_phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Required Quantity *</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                                <input type="text" class="form-control" id="unit" name="unit" placeholder="Unit (e.g., kg, tons)">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="message" class="form-label">Additional Requirements</label>
                            <textarea class="form-control" id="message" name="message" rows="4" 
                                      placeholder="Please specify any additional requirements, delivery timeline, or questions..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Send Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.category-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
</style>
@endpush

@push('scripts')
<script>
function showInquiryModal(productId, productName) {
    document.getElementById('inquiry_product_id').value = productId;
    document.getElementById('inquiry_product_name').textContent = productName;
    document.getElementById('inquiryForm').action = `/products/${productId}/inquiry`;
    
    const modal = new bootstrap.Modal(document.getElementById('inquiryModal'));
    modal.show();
}

// Handle form submission
document.getElementById('inquiryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const actionUrl = this.action;
    
    fetch(actionUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Your inquiry has been submitted successfully! We will contact you soon.');
            bootstrap.Modal.getInstance(document.getElementById('inquiryModal')).hide();
            document.getElementById('inquiryForm').reset();
        } else {
            alert('There was an error submitting your inquiry. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error submitting your inquiry. Please try again.');
    });
});
</script>
@endpush