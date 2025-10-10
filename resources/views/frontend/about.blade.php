@extends('layouts.frontend')

@section('title', 'About Us - Leading Import Export Company')
@section('meta_description', 'Learn about our company history, mission, and commitment to providing quality import-export services worldwide. Trusted partner for global trade solutions.')

@section('content')
<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold mb-4" data-aos="fade-up">About Our Company</h1>
                <p class="lead" data-aos="fade-up" data-aos-delay="100">
                    Building bridges in global trade with integrity, quality, and excellence since {{ $companyProfile->established_year ?? '2008' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Company Overview -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-4">Our Story</h2>
                <p class="lead mb-4">
                    {{ $companyProfile->company_name ?? 'ImportExport Business' }} has been at the forefront of international trade for over {{ $stats['years_experience'] }} years, connecting businesses across {{ $stats['countries_exported'] }}+ countries.
                </p>
                <p class="mb-4">
                    {!! $companyProfile->about_us ?? 'We started with a simple vision: to make global trade accessible, reliable, and profitable for businesses of all sizes. Today, we are proud to be one of the leading import-export companies, specializing in agricultural products, spices, food items, and industrial goods.' !!}
                </p>
                
                <div class="row g-4 mt-4">
                    <div class="col-6">
                        <div class="text-center">
                            <h3 class="display-6 fw-bold text-primary">{{ $stats['years_experience'] }}+</h3>
                            <p class="mb-0">Years Experience</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h3 class="display-6 fw-bold text-primary">{{ $stats['countries_exported'] }}+</h3>
                            <p class="mb-0">Countries Served</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h3 class="display-6 fw-bold text-primary">{{ $stats['products_count'] }}+</h3>
                            <p class="mb-0">Products Available</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h3 class="display-6 fw-bold text-primary">{{ $stats['happy_customers'] }}+</h3>
                            <p class="mb-0">Happy Customers</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Our Company" class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <button class="btn btn-light btn-lg rounded-circle" style="width: 80px; height: 80px;" data-bs-toggle="modal" data-bs-target="#videoModal">
                            <i class="fas fa-play text-primary"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission, Vision, Values -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-bullseye fa-3x text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Our Mission</h3>
                    <p class="text-muted">
                        {{ $companyProfile->mission ?? 'To facilitate seamless global trade by providing high-quality products, reliable services, and building lasting partnerships with suppliers and customers worldwide.' }}
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-eye fa-3x text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Our Vision</h3>
                    <p class="text-muted">
                        {{ $companyProfile->vision ?? 'To be the most trusted and preferred global trade partner, known for our commitment to quality, innovation, and customer satisfaction in international commerce.' }}
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-heart fa-3x text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Our Values</h3>
                    <p class="text-muted">
                        {{ $companyProfile->values ?? 'Integrity, Quality, Reliability, Innovation, and Customer-centricity guide everything we do. We believe in building long-term relationships based on trust and mutual success.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Why Choose Us</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    We stand out in the competitive global trade market through our commitment to excellence and customer satisfaction.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-certificate text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold mb-2">Quality Certifications</h4>
                        <p class="text-muted mb-0">All our products meet international quality standards including ISO, HACCP, and organic certifications.</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-shipping-fast text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold mb-2">Efficient Logistics</h4>
                        <p class="text-muted mb-0">Advanced supply chain management ensures timely delivery and optimal packaging for all shipments.</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-handshake text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold mb-2">Trusted Partnerships</h4>
                        <p class="text-muted mb-0">Long-term relationships with verified suppliers and distributors across the globe.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-dollar-sign text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold mb-2">Competitive Pricing</h4>
                        <p class="text-muted mb-0">Direct sourcing and bulk purchasing power allows us to offer the most competitive prices in the market.</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-headset text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold mb-2">24/7 Support</h4>
                        <p class="text-muted mb-0">Round-the-clock customer service to assist with inquiries, orders, and after-sales support.</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold mb-2">Market Expertise</h4>
                        <p class="text-muted mb-0">Deep understanding of global markets, trade regulations, and emerging opportunities.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Our Leadership Team</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Meet the experienced professionals who drive our success and ensure exceptional service delivery.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card text-center p-4">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                         alt="CEO" class="rounded-circle mx-auto mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    <h4 class="fw-bold mb-2">John Anderson</h4>
                    <p class="text-primary mb-3">Chief Executive Officer</p>
                    <p class="text-muted">Over 20 years of experience in international trade and business development.</p>
                    <div class="social-links">
                        <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card text-center p-4">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                         alt="COO" class="rounded-circle mx-auto mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    <h4 class="fw-bold mb-2">Sarah Johnson</h4>
                    <p class="text-primary mb-3">Chief Operations Officer</p>
                    <p class="text-muted">Expert in supply chain management and logistics optimization with 15+ years experience.</p>
                    <div class="social-links">
                        <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card text-center p-4">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                         alt="Sales Director" class="rounded-circle mx-auto mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    <h4 class="fw-bold mb-2">Michael Chen</h4>
                    <p class="text-primary mb-3">Sales Director</p>
                    <p class="text-muted">Specializes in building strategic partnerships and expanding global market presence.</p>
                    <div class="social-links">
                        <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
@if($testimonials->count() > 0)
<section class="section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Client Testimonials</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Hear what our valued clients have to say about their experience working with us.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($testimonials->take(6) as $testimonial)
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        @if($testimonial->image)
                            <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->customer_name }}" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h6 class="fw-bold mb-1">{{ $testimonial->customer_name }}</h6>
                            @if($testimonial->company_name)
                                <small class="text-muted">{{ $testimonial->company_name }}</small>
                            @endif
                            @if($testimonial->country)
                                <br><small class="text-muted">{{ $testimonial->country }}</small>
                            @endif
                        </div>
                        
                        <div class="ms-auto text-warning">
                            {!! $testimonial->stars !!}
                        </div>
                    </div>
                    
                    <p class="text-muted mb-0">"{{ $testimonial->message }}"</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="section-padding" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-4" data-aos="fade-up">Ready to Partner with Us?</h2>
                <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">
                    Join our network of satisfied customers and experience the difference of working with a trusted global trade partner.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Contact Us Today
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>Browse Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">About Our Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Company Video" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection