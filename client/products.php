<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "prodx_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get category from URL if set
$category = isset($_GET['category']) ? $_GET['category'] : null;

// Prepare the query
$query = "SELECT * FROM products WHERE status = 2";
if ($category) {
    $query .= " AND category = " . intval($category);
}

// Execute query
$result = mysqli_query($conn, $query);

// Get total number of products
$total_products = mysqli_num_rows($result);
?>
<?php include 'includes/header.php'; ?>

    <!--Body Content-->
    <div id="page-content">
        <!--Collection Banner-->
        <div class="collection-header">
            <div class="collection-hero">
                <div class="collection-hero__image blur-up lazyload"></div>
                <div class="collection-hero__title-wrapper">
                    <h1 class="collection-hero__title">DOST X - Assisted Products</h1>
                </div>
            </div>
        </div>
        <!--End Collection Banner-->

        <div class="container">
            <div class="row">
                <!--Sidebar-->
                <div class="col-12 col-sm-12 col-md-3 col-lg-3 sidebar filterbar">
                    <div class="closeFilter d-block d-md-none"><i class="icon icon anm anm-times-l"></i></div>
                    <div class="sidebar_tags">
                        <!--Categories-->
                        <div class="sidebar_widget categories">
                            <div class="widget-title"><h2>Categories</h2></div>
                            <div class="widget-content">
                                <ul class="sidebar_categories">
                                    <li class="lvl-1"><a href="products.php?category=1" class="site-nav">DOST - Assisted Products</a></li>
                                    <li class="lvl-1"><a href="products.php?category=2" class="site-nav">Agricultural Products</a></li>
                                    <li class="lvl-1"><a href="products.php?category=3" class="site-nav">Food Products</a></li>
                                </ul>
                            </div>
                        </div>
                        <!--Categories-->
                        
                        <!--Price Filter-->
                        <div class="sidebar_widget filterBox">
                            <div class="widget-title"><h2>Price</h2></div>
                            <form action="#" method="post" class="price-filter">
                                <div id="slider-range" class="price-range"></div>
                                <p class="amount">
                                    <input type="text" name="price" id="amount" disabled="" />
                                </p>
                                <button class="btn btn-small">Filter</button>
                            </form>
                        </div>
                        <!--End Price Filter-->
                        
                        <!--Availability-->
                        <div class="sidebar_widget filterBox">
                            <div class="widget-title"><h2>Availability</h2></div>
                            <div class="widget-content">
                                <div class="filter-item">
                                    <input type="checkbox" value="in-stock" id="in-stock" />
                                    <label for="in-stock">In Stock</label>
                                </div>
                                <div class="filter-item">
                                    <input type="checkbox" value="made-to-order" id="made-to-order" />
                                    <label for="made-to-order">Made to Order</label>
                                </div>
                            </div>
                        </div>
                        <!--End Availability-->
                    </div>
                </div>
                <!--End Sidebar-->

                <!--Main Content-->
                <div class="col-12 col-sm-12 col-md-9 col-lg-9 main-col">
                    <div class="category-description">
                        <p>Browse through our collection of DOST X - Assisted Products. Each product represents innovation, sustainability, and local craftsmanship.</p>
                    </div>
                    
                    <div class="toolbar">
                        <div class="filters-toolbar-wrapper">
                            <div class="row">
                                <div class="col-4 col-md-4 col-lg-4 filters-toolbar__item">
                                    <label for="SortBy" class="label">Sort</label>
                                    <select name="SortBy" id="SortBy" class="filters-toolbar__input filters-toolbar__input--sort">
                                        <option value="manual">Featured</option>
                                        <option value="best-selling">Best Selling</option>
                                        <option value="title-ascending">Alphabetically, A-Z</option>
                                        <option value="title-descending">Alphabetically, Z-A</option>
                                        <option value="price-ascending">Price, low to high</option>
                                        <option value="price-descending">Price, high to low</option>
                                    </select>
                                </div>
                                <div class="col-4 col-md-4 col-lg-4 text-center filters-toolbar__item">
                                    <span class="filters-toolbar__product-count">Showing: <span id="product-count">12</span> products</span>
                                </div>
                                <div class="col-4 col-md-4 col-lg-4 text-right">
                                    <div class="filters-toolbar__item">
                                        <label for="ShowBy" class="label">Show</label>
                                        <select name="ShowBy" id="ShowBy" class="filters-toolbar__input filters-toolbar__input--show">
                                            <option value="12">12</option>
                                            <option value="24">24</option>
                                            <option value="36">36</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid-products grid--view-items">
                        <div class="row">
                            <?php
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Handle product images
                                    $product_pictures = $row['product_pictures'];
                                    
                                    // Set default image
                                    $main_image = 'assets/images/product-images/product-image1.jpg';
                                    $hover_image = $main_image;
                                    
                                    if (!empty($product_pictures)) {
                                        // Parse the JSON string into an array
                                        $images = json_decode($product_pictures, true);
                                        
                                        if (!empty($images)) {
                                            // Use the first image as main image
                                            $main_image = '../' . $images[0];
                                            
                                            // Set hover image if available
                                            if (isset($images[1])) {
                                                $hover_image = '../' . $images[1];
                                            } else {
                                                $hover_image = $main_image;
                                            }
                                        }
                                    }
                                    
                                    // Clean and prepare product data
                                    $product_name = htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8');
                                    $product_description = htmlspecialchars($row['product_description'], ENT_QUOTES, 'UTF-8');
                                    $product_price = number_format($row['product_price'], 2);
                            ?>
                            <!-- Product Grid Item -->
                            <div class="col-6 col-sm-6 col-md-4 col-lg-4 item">
                                <div class="product-image" style="position: relative; width: 100%; height: 300px; overflow: hidden; border-radius: 8px;">
                                    <a href="javascript:void(0)" class="grid-view-item__link quick-view" 
                                       data-toggle="modal" 
                                       data-target="#content_quickview"
                                       data-product-id="<?php echo intval($row['product_id']); ?>"
                                       data-product-name="<?php echo $product_name; ?>"
                                       data-product-description="<?php echo $product_description; ?>"
                                       data-product-images='<?php echo $product_pictures; ?>'
                                       data-product-category="<?php echo htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8'); ?>"
                                       data-product-price="<?php echo $product_price; ?>"
                                       data-user-id="<?php echo intval($row['user_id']); ?>"
                                       style="display: block; width: 100%; height: 100%;">
                                        <div style="position: relative; width: 100%; height: 100%; overflow: hidden;">
                                            <img class="grid-view-item__image primary blur-up lazyload" 
                                                 data-src="<?php echo htmlspecialchars($main_image); ?>" 
                                                 src="<?php echo htmlspecialchars($main_image); ?>" 
                                                 alt="<?php echo $product_name; ?>" 
                                                 title="<?php echo $product_name; ?>"
                                                 style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;"
                                                 onerror="this.onerror=null; this.src='assets/images/product-images/product-image1.jpg';">
                                            <img class="grid-view-item__image hover blur-up lazyload" 
                                                 data-src="<?php echo htmlspecialchars($hover_image); ?>" 
                                                 src="<?php echo htmlspecialchars($hover_image); ?>" 
                                                 alt="<?php echo $product_name; ?>" 
                                                 title="<?php echo $product_name; ?>"
                                                 style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 2; opacity: 0; transform: translateZ(0);"
                                                 onerror="this.onerror=null; this.src='assets/images/product-images/product-image1.jpg';">
                                        </div>
                                    </a>
                                    <form class="variants add" action="#" method="post" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); width: 90%; z-index: 3;">
                                        <button class="btn btn-addto-cart quick-view" type="button" 
                                                onclick="window.location.href='product-details.php?id=<?php echo intval($row['product_id']); ?>'"
                                                style="width: 100%; background: rgba(255, 255, 255, 0.9); color: #333; border: none; padding: 8px 15px; border-radius: 4px; font-weight: 500;">
                                            View Item
                                        </button>
                                    </form>
                                    <div class="button-set" style="position: absolute; top: 10px; right: 10px; z-index: 3;">
                                        <a href="javascript:void(0)" title="Quick View" class="quick-view-popup quick-view" 
                                           data-toggle="modal" 
                                           data-target="#content_quickview"
                                           data-product-id="<?php echo intval($row['product_id']); ?>"
                                           data-product-name="<?php echo $product_name; ?>"
                                           data-product-description="<?php echo $product_description; ?>"
                                           data-product-images='<?php echo $product_pictures; ?>'
                                           data-product-category="<?php echo htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8'); ?>"
                                           data-product-price="<?php echo $product_price; ?>"
                                           data-user-id="<?php echo intval($row['user_id']); ?>"
                                           style="display: flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: rgba(255, 255, 255, 0.9); border-radius: 50%; color: #333; text-decoration: none;">
                                            <i class="icon anm anm-search-plus-r"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-details text-center" style="padding: 15px 0;">
                                    <div class="product-name" style="margin-bottom: 8px;">
                                        <a href="javascript:void(0)" class="quick-view" 
                                           data-toggle="modal" 
                                           data-target="#content_quickview"
                                           data-product-id="<?php echo intval($row['product_id']); ?>"
                                           data-product-name="<?php echo $product_name; ?>"
                                           data-product-description="<?php echo $product_description; ?>"
                                           data-product-images='<?php echo $product_pictures; ?>'
                                           data-product-category="<?php echo htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8'); ?>"
                                           data-product-price="<?php echo $product_price; ?>"
                                           data-user-id="<?php echo intval($row['user_id']); ?>"
                                           style="color: #333; text-decoration: none; font-weight: 500;">
                                            <?php echo $product_name; ?>
                                        </a>
                                    </div>
                                    <div class="product-price">
                                        <span class="price" style="color: #333; font-weight: 500;">₱<?php echo $product_price; ?></span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Product Grid Item -->
                            <?php
                                }
                            } else {
                                echo "<p>No products found in this category.</p>";
                            }
                            mysqli_close($conn);
                            ?>
                        </div>
                    </div>

                    <!--Pagination-->
                    <?php if ($total_products > 0) { ?>
                    <div class="pagination">
                        <ul>
                            <?php
                            $total_pages = ceil($total_products / 12);
                            for ($i = 1; $i <= $total_pages; $i++) {
                                $active = ($i == 1) ? ' class="active"' : '';
                                echo '<li' . $active . '><a href="?page=' . $i . ($category ? '&category=' . $category : '') . '">' . $i . '</a></li>';
                            }
                            if ($total_pages > 1) {
                                echo '<li class="next"><a href="#"><i class="fa fa-caret-right"></i></a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <?php } ?>
                    <!--End Pagination-->
                </div>
                <!--End Main Content-->
            </div>
        </div>
    </div>
    <!--End Body Content-->

<?php include 'includes/footer.php'; ?>

    <!--Scoll Top-->
    <span id="site-scroll"><i class="icon anm anm-angle-up-r"></i></span>
    <!--End Scoll Top-->

    <!-- Including Jquery -->
    <script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
    <script src="assets/js/vendor/modernizr-3.6.0.min.js"></script>
    <script src="assets/js/vendor/jquery.cookie.js"></script>
    <script src="assets/js/vendor/wow.min.js"></script>
    <!-- Including Javascript -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/lazysizes.js"></script>
    <script src="assets/js/main.js"></script>
    <!-- Add Slick Carousel -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <!--Quick View popup-->
    <div class="modal fade quick-view-popup" id="content_quickview">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="ProductSection-product-template" class="product-template__container prstyle1">
                        <div class="product-single">
                            <!-- Start model close -->
                            <a href="javascript:void()" data-dismiss="modal" class="model-close-btn pull-right" title="close"><span class="icon icon anm anm-times-l"></span></a>
                            <!-- End model close -->
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="product-details-img">
                                        <div class="product-thumb">
                                            <div id="gallery" class="product-dec-slider-2 product-tab-left">
                                                <!-- Images will be loaded dynamically -->
                                            </div>
                                            <div class="gallery-nav">
                                                <button type="button" class="prev-btn" title="Previous">
                                                    <i class="icon anm anm-angle-left-l"></i>
                                                </button>
                                                <button type="button" class="next-btn" title="Next">
                                                    <i class="icon anm anm-angle-right-l"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="product-single__meta">
                                        <h2 id="quickview-title" class="product-single__title"></h2>
                                        <div class="prInfoRow">
                                            <div class="product-stock"> <span class="instock">Made to Order</span> </div>
                                        </div>
                                        <div class="product-price" id="quickview-price">
                                            <span class="price">₱0.00</span>
                                        </div>
                                        <div class="product-single__description rte">
                                            <p id="quickview-description"></p>
                                            <a href="#" id="see-more-link" class="text-primary" style="text-decoration: none; font-weight: 500;">See more</a>
                                        </div>
                                        <div class="product-single__meta-details">
                                            <div class="product-single__meta-detail">
                                                <span class="product-single__meta-label">Category:</span>
                                                <span id="quickview-category" class="product-single__meta-value"></span>
                                            </div>
                                        </div>
                                        <div class="product-action" style="margin-top: 20px;">
                                            <a href="javascript:void(0)" class="btn btn-primary contact-seller-btn" style="width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; text-decoration: none; display: inline-block; text-align: center; font-weight: 500;">
                                                Contact Seller
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Quick View popup-->

    <style>
    .quick-view-popup .modal-dialog {
        max-width: 1000px;
        margin: 1.75rem auto;
    }
    .quick-view-popup .modal-content {
        border-radius: 8px;
        overflow: hidden;
    }
    .quick-view-popup .modal-body {
        padding: 30px;
    }
    .quick-view-popup .product-details-img {
        position: relative;
        margin-bottom: 20px;
        width: 100%;
    }
    .quick-view-popup .product-thumb {
        position: relative;
        width: 100%;
        min-height: 400px;
        overflow: hidden;
    }
    .quick-view-popup .product-image {
        margin-bottom: 15px;
        width: 100%;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f8f8;
        border-radius: 8px;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
    }
    .quick-view-popup .product-image.active {
        opacity: 1;
        visibility: visible;
        z-index: 2;
    }
    .quick-view-popup .product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 4px;
    }
    .quick-view-popup .gallery-nav {
        position: absolute;
        top: 50%;
        width: 100%;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
        padding: 0 15px;
        pointer-events: none;
        z-index: 3;
    }
    .quick-view-popup .gallery-nav button {
        background: rgba(255,255,255,0.9);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        pointer-events: auto;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        font-size: 18px;
        color: #333;
    }
    .quick-view-popup .gallery-nav button:hover {
        background: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .quick-view-popup .gallery-nav button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .quick-view-popup .gallery-nav button i {
        line-height: 1;
    }
    .quick-view-popup .product-single__title {
        font-size: 28px;
        margin-bottom: 20px;
        color: #333;
        font-weight: 600;
    }
    .quick-view-popup .product-single__description {
        margin: 20px 0;
        color: #666;
        line-height: 1.8;
        font-size: 16px;
    }
    .quick-view-popup .product-single__meta-details {
        margin: 25px 0;
        padding: 20px 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }
    .quick-view-popup .product-single__meta-detail {
        margin-bottom: 12px;
        font-size: 15px;
    }
    .quick-view-popup .product-single__meta-label {
        font-weight: 600;
        color: #333;
        margin-right: 8px;
    }
    .quick-view-popup .product-single__meta-value {
        color: #666;
    }
    .quick-view-popup .model-close-btn {
        position: absolute;
        right: 15px;
        top: 15px;
        z-index: 1;
        color: #666;
        text-decoration: none;
        font-size: 24px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.9);
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .quick-view-popup .model-close-btn:hover {
        color: #333;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .quick-view-popup .row {
        margin: 0;
    }
    .quick-view-popup .col-lg-6 {
        padding: 0 15px;
    }
    .quick-view-popup .prInfoRow {
        margin-bottom: 20px;
    }
    .quick-view-popup .instock {
        color: #28a745;
        font-weight: 500;
    }
    .quick-view-popup .product-price {
        font-size: 20px;
        color: #333;
        font-weight: 500;
        margin: 10px 0;
    }
    .quick-view-popup .product-price .price {
        color: #333;
    }
    .product-details .product-price {
        font-size: 16px;
        color: #333;
        font-weight: 500;
        margin-top: 8px;
    }
    .product-details .product-price .price {
        color: #333;
    }
    /* Add hover effect styles */
    .product-image:hover .grid-view-item__image.hover {
        opacity: 1 !important;
        transition: opacity 0.3s ease;
    }
    .grid-view-item__image.hover {
        transition: opacity 0.3s ease;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .grid-view-item__image {
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    </style>

    <script>
    $(document).ready(function() {
        let currentImage = 0;
        let images = [];
        let isTransitioning = false;
        let currentProductId = null;

        // Quick view functionality
        $('.quick-view').click(function() {
            var productId = $(this).data('product-id');
            var productName = $(this).data('product-name');
            var productDescription = $(this).data('product-description');
            var productImages = $(this).data('product-images');
            var productCategory = $(this).data('product-category');
            var productPrice = $(this).data('product-price');
            var userId = $(this).data('user-id');

            // Store current product ID
            currentProductId = productId;

            // Update modal content
            $('#quickview-title').text(productName);
            
            // Limit description to 150 characters and add ellipsis
            var limitedDescription = productDescription.length > 150 ? 
                productDescription.substring(0, 150) + '...' : 
                productDescription;
            $('#quickview-description').text(limitedDescription);
            
            // Update see more link
            $('#see-more-link').attr('href', 'product-details.php?id=' + productId);
            
            $('#quickview-category').text(productCategory);
            $('#quickview-price').html('<span class="price">₱' + productPrice + '</span>');
            
            // Update contact seller button
            $('.contact-seller-btn').attr('href', 'contact_seller.php?user_id=' + userId);
            
            // Update gallery
            var gallery = $('#gallery');
            gallery.empty();
            
            // Reset state
            currentImage = 0;
            images = [];
            isTransitioning = false;
            
            // Parse product images
            if (typeof productImages === 'string') {
                productImages = JSON.parse(productImages);
            }
            
            // Add all images to gallery
            if (Array.isArray(productImages) && productImages.length > 0) {
                productImages.forEach(function(imagePath) {
                    gallery.append(`
                        <div class="product-image">
                            <img class="blur-up lazyload" 
                                 src="../${imagePath}" 
                                 alt="${productName}"
                                 onerror="this.onerror=null; this.src='assets/images/product-images/product-image1.jpg';">
                        </div>
                    `);
                });
                
                // Set first image as active
                gallery.find('.product-image').first().addClass('active');
            } else {
                // Add default image if no images available
                gallery.append(`
                    <div class="product-image active">
                        <img class="blur-up lazyload" 
                             src="assets/images/product-images/product-image1.jpg" 
                             alt="${productName}">
                    </div>
                `);
            }

            // Get all images
            images = gallery.find('.product-image');
            
            // Update navigation buttons
            updateNavigationButtons();
        });

        function updateNavigationButtons() {
            const prevBtn = $('.prev-btn');
            const nextBtn = $('.next-btn');
            
            if (images.length <= 1) {
                prevBtn.hide();
                nextBtn.hide();
            } else {
                prevBtn.show();
                nextBtn.show();
                prevBtn.prop('disabled', currentImage === 0);
                nextBtn.prop('disabled', currentImage === images.length - 1);
            }
        }

        function showImage(index) {
            if (isTransitioning || index < 0 || index >= images.length) return;
            isTransitioning = true;

            // Remove active class from current image
            images.removeClass('active');
            
            // Add active class to new image
            images.eq(index).addClass('active');
            
            // Update current image index
            currentImage = index;
            
            // Update navigation buttons
            updateNavigationButtons();
            
            // Reset transition flag after animation completes
            setTimeout(() => {
                isTransitioning = false;
            }, 300);
        }

        // Handle navigation
        $('.prev-btn').off('click').on('click', function() {
            if (currentImage > 0) {
                showImage(currentImage - 1);
            }
        });

        $('.next-btn').off('click').on('click', function() {
            if (currentImage < images.length - 1) {
                showImage(currentImage + 1);
            }
        });

        // Update product count
        $('#product-count').text(<?php echo $total_products; ?>);
    });
    </script>
</div>
</body>
</html> 