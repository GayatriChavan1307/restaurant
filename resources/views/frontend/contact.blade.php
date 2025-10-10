@extends('layouts.frontend')

@section('title', 'Contact Us - Get in Touch for Import Export Services')
@section('meta_description', 'Contact our import export company for inquiries, quotes, and business partnerships. Multiple ways to reach us including phone, email, and contact form.')

@section('content')
<!-- Page Header -->
<section class="hero-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold mb-4" data-aos="fade-up">Contact Us</h1>
                <p class="lead" data-aos="fade-up" data-aos-delay="100">
                    Get in touch with our team for inquiries, quotes, or to discuss your import-export requirements
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-map-marker-alt fa-3x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Visit Our Office</h4>
                    <p class="text-muted mb-0">
                        {{ $companyProfile->full_address ?? '123 Business Street, Trade City, TC 12345, United States' }}
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-phone fa-3x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Call Us</h4>
                    <p class="text-muted mb-2">
                        <strong>Phone:</strong> {{ $companyProfile->phone ?? '+1 (555) 123-4567' }}
                    </p>
                    <p class="text-muted mb-0">
                        <strong>Hours:</strong> Mon - Fri: 9:00 AM - 6:00 PM
                    </p>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 text-center p-4">
                    <div class="mb-4">
                        <i class="fas fa-envelope fa-3x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Email Us</h4>
                    <p class="text-muted mb-2">
                        <strong>General:</strong> {{ $companyProfile->email ?? 'info@importexport.com' }}
                    </p>
                    <p class="text-muted mb-0">
                        <strong>Sales:</strong> sales@importexport.com
                    </p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8" data-aos="fade-right">
                <div class="card p-4">
                    <h3 class="fw-bold mb-4">Send Us a Message</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="subject" class="form-label">Subject *</label>
                                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">Select Subject</option>
                                    <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="Product Quote" {{ old('subject') == 'Product Quote' ? 'selected' : '' }}>Product Quote</option>
                                    <option value="Partnership" {{ old('subject') == 'Partnership' ? 'selected' : '' }}>Partnership Opportunity</option>
                                    <option value="Support" {{ old('subject') == 'Support' ? 'selected' : '' }}>Customer Support</option>
                                    <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="6" required 
                                          placeholder="Please provide details about your inquiry...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4" data-aos="fade-left">
                <div class="card p-4 mb-4">
                    <h4 class="fw-bold mb-3">Quick Response</h4>
                    <p class="text-muted mb-3">
                        We typically respond to inquiries within 24 hours during business days.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>Response Time:</strong> Within 24 hours
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-language text-primary me-2"></i>
                            <strong>Languages:</strong> English, Spanish, Chinese
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-globe text-primary me-2"></i>
                            <strong>Time Zone:</strong> UTC-5 (EST)
                        </li>
                    </ul>
                </div>
                
                <div class="card p-4">
                    <h4 class="fw-bold mb-3">Need Immediate Help?</h4>
                    <p class="text-muted mb-3">
                        For urgent inquiries, please call us directly or use our live chat feature.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="tel:{{ $companyProfile->phone ?? '+15551234567' }}" class="btn btn-outline-primary">
                            <i class="fas fa-phone me-2"></i>Call Now
                        </a>
                        <button class="btn btn-outline-secondary" onclick="openLiveChat()">
                            <i class="fas fa-comments me-2"></i>Live Chat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Find Us on the Map</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Visit our office or warehouse facilities. We welcome scheduled appointments for business meetings.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12" data-aos="fade-up">
                <div class="card">
                    <div class="card-body p-0">
                        <!-- Google Maps Embed -->
                        <div class="ratio ratio-21x9">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1841!2d-73.98731!3d40.75889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae072e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1635959542000!5m2!1sen!2sus" 
                                    style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section-padding">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Frequently Asked Questions</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">
                    Find answers to common questions about our services and processes.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto" data-aos="fade-up">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                What products do you specialize in?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We specialize in agricultural products, spices, food items, and industrial goods. Our main focus is on high-quality onions, onion powder, various spices, grains, and processed food products.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                What countries do you export to?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We export to over 50 countries worldwide including USA, Canada, Europe, Middle East, Asia-Pacific, and Africa. Our global network allows us to reach virtually any destination.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                What are your minimum order quantities?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Minimum order quantities vary by product. Generally, we require a minimum of 1 container (20ft) for most products. Please contact us for specific MOQ requirements for your desired products.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                How long does shipping take?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Shipping times depend on the destination and shipping method. Typically, sea freight takes 15-45 days, while air freight takes 3-7 days. We provide detailed shipping schedules with every quote.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                                Do you provide quality certificates?
                            </button>
                        </h2>
                        <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, we provide all necessary quality certificates including phytosanitary certificates, certificates of origin, quality analysis reports, and any other documentation required by your country's import regulations.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-4" data-aos="fade-up">Ready to Start Your Import Export Journey?</h2>
                <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">
                    Contact us today to discuss your requirements and get a personalized quote for your business needs.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-search me-2"></i>Browse Products
                    </a>
                    <a href="tel:{{ $companyProfile->phone ?? '+15551234567' }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Call Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function openLiveChat() {
    // Implement live chat functionality here
    alert('Live chat feature will be available soon. Please call us or send an email for immediate assistance.');
}
</script>
@endpush