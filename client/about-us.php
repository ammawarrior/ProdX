<?php include 'includes/header.php'; ?>

<!--Body Content-->
<div id="page-content">
    <!--Page Title-->
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper">
                <h1 class="page-width">About Us</h1>
            </div>
        </div>
    </div>
    <!--End Page Title-->

    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="about-content">
                    <div class="about-section">
                        <h2>Welcome to ProDX</h2>
                        <p>ProDX is the Digital Catalog for DOST X - Assisted Products, a centralized platform showcasing innovations and their broader impact on communities and industries. Our name, pronounced as "products," combines D for DOST and X for Region 10, highlighting our regional focus and purpose.</p>
                    </div>

                    <div class="about-section">
                        <h2>Our Mission</h2>
                        <p>We are dedicated to promoting and supporting local innovations and products developed with the assistance of DOST Region 10. Our platform serves as a bridge between innovative products and potential customers, fostering growth and development in our region.</p>
                    </div>

                    <div class="about-section">
                        <h2>What We Do</h2>
                        <ul class="feature-list">
                            <li>Showcase DOST-assisted products and innovations</li>
                            <li>Connect local producers with potential customers</li>
                            <li>Promote sustainable and innovative solutions</li>
                            <li>Support regional economic development</li>
                        </ul>
                    </div>

                    <div class="about-section contact-section">
                        <h2>Contact Us</h2>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="icon anm anm-map-marker-al"></i>
                                <div>
                                    <h4>Address</h4>
                                    <p>DOST Region 10 Office<br>
                                    Cagayan de Oro City, Philippines</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="icon anm anm-phone-s"></i>
                                <div>
                                    <h4>Phone</h4>
                                    <p>(63) 915 123 456</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="icon anm anm-envelope-l"></i>
                                <div>
                                    <h4>Email</h4>
                                    <p>contact@region10.dost.gov.ph</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Body Content-->

<style>
.about-content {
    padding: 40px 0;
}

.about-section {
    margin-bottom: 40px;
}

.about-section h2 {
    color: #333;
    font-size: 28px;
    margin-bottom: 20px;
    font-weight: 600;
}

.about-section p {
    color: #666;
    line-height: 1.8;
    font-size: 16px;
    margin-bottom: 20px;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-list li {
    color: #666;
    padding: 10px 0;
    padding-left: 30px;
    position: relative;
    font-size: 16px;
}

.feature-list li:before {
    content: "✓";
    color: #007bff;
    position: absolute;
    left: 0;
    font-weight: bold;
}

.contact-section {
    background: #f8f9fa;
    padding: 40px;
    border-radius: 8px;
}

.contact-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.contact-item i {
    font-size: 24px;
    color: #007bff;
    margin-top: 5px;
}

.contact-item h4 {
    color: #333;
    font-size: 18px;
    margin: 0 0 10px 0;
}

.contact-item p {
    color: #666;
    margin: 0;
    line-height: 1.6;
}

@media (max-width: 767px) {
    .about-section h2 {
        font-size: 24px;
    }
    
    .contact-section {
        padding: 20px;
    }
    
    .contact-info {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?> 