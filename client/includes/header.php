<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title><?php echo $product_name; ?> - proDX</title>
<meta name="description" content="<?php echo substr($product_description, 0, 160); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Favicon -->
<link rel="shortcut icon" href="assets/images/favicon.png" />
<!-- Plugins CSS -->
<link rel="stylesheet" href="assets/css/plugins.css">
<!-- Bootstap CSS -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<!-- Main Style CSS -->
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/responsive.css">
<style>
.product-single__title {
    font-size: 24px;
    margin-bottom: 15px;
    color: #333;
}
.product-single__price {
    font-size: 20px;
    color: #333;
    margin: 15px 0;
}
.product-single__description {
    margin: 20px 0;
    line-height: 1.6;
    color: #666;
}
.product-details-img {
    position: relative;
    margin-bottom: 0;
    padding: 0;
}
.product-thumb {
    position: relative;
    width: 100%;
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.main-image-container {
    position: relative;
    width: 100%;
    height: 400px;
    background: #fff;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.product-image-item {
    display: none;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}
.product-image-item.active {
    display: block;
}
.product-image-item img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
}
.thumbnail-container {
    display: flex;
    gap: 10px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 0 0 8px 8px;
    overflow-x: auto;
    scrollbar-width: thin;
    justify-content: center;
}
.thumbnail-container::-webkit-scrollbar {
    height: 4px;
}
.thumbnail-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}
.thumbnail-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 2px;
}
.thumbnail-item {
    flex: 0 0 80px;
    height: 80px;
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    background: #fff;
    padding: 2px;
}
.thumbnail-item.active {
    border-color: #007bff;
}
.thumbnail-item:hover {
    border-color: #0056b3;
}
.thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.breadcrumb {
    padding: 15px 0;
    margin-bottom: 30px;
    background: none;
}
.breadcrumb a {
    color: #666;
    text-decoration: none;
}
.breadcrumb a:hover {
    color: #333;
}
.contact-info p {
    margin: 10px 0;
    color: #666;
}
.contact-info i {
    margin-right: 10px;
    color: #007bff;
}
.prInfoRow {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}
.product-stock .instock {
    color: #28a745;
    font-weight: 500;
}
.product-sku {
    color: #666;
}
.variant-sku {
    color: #333;
    font-weight: 500;
}
@media (max-width: 767px) {
    .product-details-img {
        padding: 10px;
    }
    .main-image-container {
        height: 300px;
    }
    .thumbnail-item {
        flex: 0 0 60px;
        height: 60px;
    }
    .product-single__title {
        font-size: 20px;
    }
    .product-single__price {
        font-size: 18px;
    }
}
/* Adjust container padding for product details page */
#page-content > .container {
    padding-left: 15px;
    padding-right: 15px;
}

/* Override product-thumb width */
.product-details-img .product-thumb {
    width: 100%;
    float: none;
    display: block;
}

/* Styles for zoom feature */
.zoom-container {
    position: relative;
    overflow: hidden;
}

.zoom-window {
    position: absolute;
    border: 1px solid #ccc;
    background-repeat: no-repeat;
    display: none; /* Hidden by default */
    z-index: 100;
    pointer-events: none; /* Ignore mouse events */
    width: 300px; /* Adjust size as needed */
    height: 300px; /* Adjust size as needed */
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

/* Temporary debugging styles */
.zoom-window {
    background-color: rgba(255, 0, 0, 0.5) !important; /* Semi-transparent red */
    position: fixed !important; /* Fixed position relative to viewport */
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important; /* Center the window */
    z-index: 9999 !important; /* High z-index */
    display: none; /* Keep hidden by default, JavaScript will show it */
}

.product-image-item img.main-product-image {
    display: block;
    width: 100%; /* Ensure image fills its container */
}
</style>
</head>
<body class="template-index">
	<div id="pre-loader">
		<img src="assets/images/loader.gif" alt="Loading..." />
	</div>
	<div class="pageWrapper">
        <!--Search Form Drawer-->
        <div class="search">
            <div class="search__form">
                <form class="search-bar__form" action="#">
                    <button class="go-btn search__button" type="submit"><i class="icon anm anm-search-l"></i></button>
                    <input class="search__input" type="search" name="q" value="" placeholder="Search entire store..." aria-label="Search" autocomplete="off">
                </form>
                <button type="button" class="search-trigger close-btn"><i class="anm anm-times-l"></i></button>
            </div>
        </div>
        <!--End Search Form Drawer-->
        <!--Header-->
        <div class="header-wrap animated d-flex border-bottom">
            <div class="container-fluid">        
                <div class="row align-items-center">
                    <!--Desktop Logo-->
                    <div class="logo col-md-2 col-lg-2 d-none d-lg-block">
                        <a href="index.php">
                            <img src="assets/images/logo.svg" alt="proDX: The Digital Catalog for DOST X - Assisted Products" title="proDX: The Digital Catalog for DOST X - Assisted Products" />
                        </a>
                    </div>
                    <!--End Desktop Logo-->
                    <div class="col-2 col-sm-3 col-md-3 col-lg-8">
                        <div class="d-block d-lg-none">
                            <button type="button" class="btn--link site-header__menu js-mobile-nav-toggle mobile-nav--open">
                                <i class="icon anm anm-times-l"></i>
                                <i class="anm anm-bars-r"></i>
                            </button>
                        </div>
                        <!--Desktop Menu-->
                        <nav class="grid__item" id="AccessibleNav"><!-- for mobile -->
                            <ul id="siteNav" class="site-nav medium center hidearrow">
                                <li class="lvl1"><a href="index.php">HOME <i class="anm anm-angle-down-l"></i></a></li>
                                <li class="lvl1"><a href="products.php">PRODUCTS</a></li>
                                <li class="lvl1"><a href="about-us.php">ABOUT US</a></li>
                            </ul>
                        </nav>
                        <!--End Desktop Menu-->
                    </div>
                    <!--Mobile Logo-->
                    <div class="col-6 col-sm-6 col-md-6 col-lg-2 d-block d-lg-none mobile-logo">
                        <div class="logo">
                            <a href="index.php">
                                <img src="assets/images/logo.svg" alt="proDX: The Digital Catalog for DOST X - Assisted Products" title="proDX: The Digital Catalog for DOST X - Assisted Products" />
                            </a>
                        </div>
                    </div>
                    <!--Mobile Logo-->
                </div>
            </div>
        </div>
        <!--End Header-->
        <!--Mobile Menu-->
        <div class="mobile-nav-wrapper" role="navigation">
            <div class="closemobileMenu"><i class="icon anm anm-times-l pull-right"></i> Close Menu</div>
            <ul id="MobileNav" class="mobile-nav">
                <li class="lvl1"><a href="index.php">HOME</a>
            </li>
            <li class="lvl1"><a href="products.php">PRODUCTS</a>
            </li>
            <li class="lvl1"><a href="about-us.php">ABOUT US</a>
            </li>
          </ul>
        </div>
        <!--End Mobile Menu-->
    </div>
</body>
</html> 