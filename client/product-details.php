<?php
session_start();
require_once '../prodx_db.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$query = "SELECT p.*, u.code_name, u.email, u.user_picture, u.contact_number 
          FROM products p 
          JOIN users u ON p.user_id = u.user_id 
          WHERE p.product_id = ? AND u.role = 2";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    // Handle product images
    $product_pictures = $row['product_pictures'];
    $images = [];
    
    if (!empty($product_pictures)) {
        $images = json_decode($product_pictures, true);
    }
    
    // Clean and prepare product data
    $product_name = htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8');
    $product_description = htmlspecialchars($row['product_description'], ENT_QUOTES, 'UTF-8');
    $product_price = number_format($row['product_price'], 2);
    $category = htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8');
    $seller_name = htmlspecialchars($row['code_name'], ENT_QUOTES, 'UTF-8');
    $seller_email = htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8');
    $seller_phone = htmlspecialchars($row['contact_number'], ENT_QUOTES, 'UTF-8');
    $seller_picture = !empty($row['user_picture']) ? '../' . $row['user_picture'] : 'assets/images/user-placeholder.jpg';
} else {
    // Product not found
    header("Location: products.php");
    exit();
}
?>

<?php include 'includes/header.php'; ?>

        <!--Body Content-->
        <div id="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <nav class="breadcrumb" role="navigation" aria-label="breadcrumbs">
                            <a href="index.php" title="Back to the frontpage">Home</a>
                            <span aria-hidden="true">/</span>
                            <a href="products.php">Products</a>
                            <span aria-hidden="true">/</span>
                            <span><?php echo $product_name; ?></span>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-7 col-lg-7">
                        <div class="product-details-img">
                            <div class="product-thumb">
                                <!-- Main Image Display -->
                                <div class="zoom-container">
                                <div id="main-image" class="main-image-container">
                                    <?php if (!empty($images)): ?>
                                        <?php foreach ($images as $index => $image): ?>
                                            <div class="product-image-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                <img src="<?php echo '../' . $image; ?>" 
                                                     alt="<?php echo $product_name; ?>" 
                                                     title="<?php echo $product_name; ?>"
                                                     class="blur-up lazyload main-product-image"
                                                     onerror="this.onerror=null; this.src='assets/images/product-images/product-image1.jpg';">
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="product-image-item active">
                                            <img src="assets/images/product-images/product-image1.jpg" 
                                                 alt="<?php echo $product_name; ?>" 
                                                 title="<?php echo $product_name; ?>"
                                                 class="blur-up lazyload main-product-image">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Add the zoom window div -->
                                <div class="zoom-window"></div>
                                </div>

                                <!-- Thumbnail Navigation -->
                                <?php if (count($images) > 1): ?>
                                <div class="thumbnail-container">
                                    <?php foreach ($images as $index => $image): ?>
                                        <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                            <img src="<?php echo '../' . $image; ?>" 
                                                 alt="<?php echo $product_name; ?>" 
                                                 title="<?php echo $product_name; ?>"
                                                 class="blur-up lazyload"
                                                 onerror="this.onerror=null; this.src='assets/images/product-images/product-image1.jpg';">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="product-single__meta">
                            <h1 class="product-single__title"><?php echo $product_name; ?></h1>
                            <div class="prInfoRow">
                                <div class="product-sku">
                                    Category: <span class="variant-sku"><?php echo $category; ?></span>
                                </div>
                            </div>
                            <div class="product-single__price">
                                <span class="price">₱<?php echo $product_price; ?></span>
                            </div>
                            <div class="product-single__description rte">
                                <?php echo nl2br($product_description); ?>
                            </div>
                            
                            <!-- Contact Seller Button -->
                            <div class="contact-seller-section" style="margin-top: 30px;">
                                <a href="contact_seller.php?user_id=<?php echo $row['user_id']; ?>" 
                                   class="btn btn-primary" 
                                   style="width: 100%; padding: 12px; font-size: 16px; font-weight: 500;">
                                    Contact Seller
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    	<!--End Body Content-->
    
<?php include 'includes/footer.php'; ?>

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
        margin-bottom: 30px;
        padding: 15px;
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

    <script>
    $(document).ready(function() {
        // Declare variables at the beginning
        const zoomContainer = $('.zoom-container');
        const mainImage = $('.main-product-image');
        const zoomWindow = $('.zoom-window');
        const zoomFactor = 2; // Adjust zoom level as needed
        let isTransitioning = false;
        let naturalImageWidth = 0;
        let naturalImageHeight = 0;
        let renderedImageWidth = 0; // Store rendered width
        let renderedImageHeight = 0; // Store rendered height
        let currentImageSrcForZoom = ''; // Track the image source for which dimensions are being loaded
        let currentImageSrcForZoomAbsolute = ''; // Track the absolute image source for comparison

        let currentImage = 0;
        const images = $('.product-image-item');
        const thumbnails = $('.thumbnail-item');
        const totalImages = images.length;

        // Helper function to update zoom window background image
        function updateZoomImageBackground(imageSrc) {
            if (!imageSrc) {
                zoomWindow.css('background-image', ''); // Clear background if no source
                return;
            }
            // Set the background image of the zoom window
            zoomWindow.css('background-image', 'url(' + imageSrc + ')');
        }

         // Function to load image and get natural dimensions
        function loadAndGetNaturalDimensions(imageSrc) {
             currentImageSrcForZoom = imageSrc; // Store the original image source

             // Convert relative imageSrc to absolute for reliable comparison
             const urlResolver = document.createElement('a'); // Use an anchor element to resolve the URL
             urlResolver.href = imageSrc;
             currentImageSrcForZoomAbsolute = urlResolver.href;

             naturalImageWidth = 0; // Reset dimensions while loading
             naturalImageHeight = 0;
             renderedImageWidth = 0; // Reset rendered dimensions
             renderedImageHeight = 0;

            if (!imageSrc) return;

            const tempImage = new Image();
            tempImage.onload = function() {
                // console.log('tempImage.src:', tempImage.src); // Debugging log
                // console.log('currentImageSrcForZoomAbsolute:', currentImageSrcForZoomAbsolute); // Debugging log
                // Compare absolute URLs
                if (tempImage.src === currentImageSrcForZoomAbsolute) {
                    naturalImageWidth = this.naturalWidth;
                    naturalImageHeight = this.naturalHeight;
                    // console.log('Image loaded, natural dimensions:', naturalImageWidth, naturalImageHeight); // Debugging log

                    // Get and store rendered dimensions of the active image element
                    const activeImageElement = mainImage.closest('.product-image-item.active').find('img');
                    renderedImageWidth = activeImageElement.width();
                    renderedImageHeight = activeImageElement.height();
                     // console.log('Image rendered, dimensions:', renderedImageWidth, renderedImageHeight); // Debugging log;

                    // Trigger a mousemove to update the background position immediately
                    // Check if the mouse is still hovering before triggering, although mousemove has its own check
                    // if (zoomContainer.is(':hover')) {
                         zoomContainer.trigger('mousemove');
                    // }
                } else {
                     // console.log('Loaded image is not the current active image for zoom.', tempImage.src, currentImageSrcForZoomAbsolute); // Debugging log;
                }
            };
            tempImage.onerror = function() {
                 // console.error('Error loading image for natural dimensions:', imageSrc); // Debugging log
                 // Reset dimensions if loading fails for the current image
                 if (tempImage.src === currentImageSrcForZoomAbsolute) {
                      naturalImageWidth = 0;
                      naturalImageHeight = 0;
                 }
            };
            tempImage.src = imageSrc;
        }

        // Function to show selected image
        function showImage(index) {
            if (isTransitioning || index < 0 || index >= totalImages) return;
            isTransitioning = true;

            // Update main image
            images.removeClass('active');
            images.eq(index).addClass('active');

            // Update thumbnails
            thumbnails.removeClass('active');
            thumbnails.eq(index).addClass('active');

            // Scroll thumbnail into view if needed
            const thumbnail = thumbnails.eq(index);
            const container = $('.thumbnail-container');
            const containerWidth = container.width();
            const thumbnailLeft = thumbnail.position().left;
            const thumbnailWidth = thumbnail.outerWidth();

            if (thumbnailLeft < 0) {
                container.scrollLeft(container.scrollLeft() + thumbnailLeft);
            } else if (thumbnailLeft + thumbnailWidth > containerWidth) {
                container.scrollLeft(container.scrollLeft() + (thumbnailLeft + thumbnailWidth - containerWidth));
            }

            currentImage = index;

            // Update the zoom window image and load new dimensions
            const newImageSrc = images.eq(index).find('img').attr('src');
            // console.log('showImage - New active image source:', newImageSrc); // Debugging log
            updateZoomImageBackground(newImageSrc);
            loadAndGetNaturalDimensions(newImageSrc);

            setTimeout(() => {
                isTransitioning = false;
                // Trigger a mousemove event after a short delay to update zoom view
                zoomContainer.trigger('mousemove');
            }, 100); // Short delay (e.g., 100ms)
        }

        // Thumbnail click handler
        thumbnails.on('click', function() {
            const index = $(this).data('index');
            showImage(index);
        });

        // Initialize
        if (totalImages > 1) {
            showImage(0);
        }

        // Zoom functionality event handlers
        zoomContainer.on('mouseenter', function() {
            // Get the source of the currently active main image
            const activeImageElement = mainImage.closest('.product-image-item.active').find('img');
            const activeImageSrc = $(activeImageElement).attr('src');
            // console.log('mouseenter - Active image source:', activeImageSrc); // Debugging log
            
            if (!activeImageSrc) return;

            // Ensure background is set immediately and show the zoom window
            updateZoomImageBackground(activeImageSrc); 
            zoomWindow.css('display', 'block');

            // Convert activeImageSrc to absolute URL for comparison
            const urlResolver = document.createElement('a');
            urlResolver.href = activeImageSrc;
            const activeImageSrcAbsolute = urlResolver.href;

            // If the image source is different or dimensions not loaded, start loading natural dimensions
            // Compare using absolute URLs
            if (activeImageSrcAbsolute !== currentImageSrcForZoomAbsolute || naturalImageWidth === 0 || naturalImageHeight === 0 || renderedImageWidth === 0 || renderedImageHeight === 0) {
                 loadAndGetNaturalDimensions(activeImageSrc);
            }
            // Trigger mousemove to update position if dimensions are already available for the current image
            // Check using absolute URLs
            if (naturalImageWidth > 0 && naturalImageHeight > 0 && renderedImageWidth > 0 && renderedImageHeight > 0 && activeImageSrcAbsolute === currentImageSrcForZoomAbsolute) {
                 zoomContainer.trigger('mousemove');
            }

        });

        zoomContainer.on('mouseleave', function() {
            // Hide the zoom window
            zoomWindow.css('display', 'none');
        });

        zoomContainer.on('mousemove', function(e) {
            // Only proceed if natural and rendered dimensions are available
            if (naturalImageWidth === 0 || naturalImageHeight === 0 || renderedImageWidth === 0 || renderedImageHeight === 0) {
                 // console.log('mousemove - Dimensions not available yet.', {naturalWidth: naturalImageWidth, naturalHeight: naturalImageHeight, renderedWidth: renderedImageWidth, renderedHeight: renderedImageHeight}); // Debugging log
                 return;
            }

            // Get the position of the container and the mouse
            const containerOffset = $(this).offset();
            const offsetX = e.pageX - containerOffset.left;
            const offsetY = e.pageY - containerOffset.top;

            // Calculate the relative position of the mouse within the displayed image (0 to 1)
            // Use stored rendered dimensions
            const relativeX = offsetX / renderedImageWidth;
            const relativeY = offsetY / renderedImageHeight;

            // Calculate the background position for the zoom window using natural dimensions and relative position
            const backgroundPosX = -(relativeX * naturalImageWidth * zoomFactor - (zoomWindow.width() / 2));
            const backgroundPosY = -(relativeY * naturalImageHeight * zoomFactor - (zoomWindow.height() / 2));

            // console.log('mousemove data:', {
            //     offsetX: offsetX,
            //     offsetY: offsetY,
            //     renderedImageWidth: renderedImageWidth,
            //     renderedImageHeight: renderedImageHeight,
            //     naturalImageWidth: naturalImageWidth,
            //     naturalImageHeight: naturalImageHeight,
            //     zoomFactor: zoomFactor,
            //     zoomWindowWidth: zoomWindow.width(),
            //     zoomWindowHeight: zoomWindow.height(),
            //     relativeX: relativeX,
            //     relativeY: relativeY,
            //     backgroundPosX: backgroundPosX,
            //     backgroundPosY: backgroundPosY,
            //     backgroundPositionCSS: backgroundPosX + 'px ' + backgroundPosY + 'px'
            // }); // Debugging log

            // Apply the background size and position using natural dimensions
            zoomWindow.css({
                'background-size': (naturalImageWidth * zoomFactor) + 'px ' + (naturalImageHeight * zoomFactor) + 'px',
                'background-position': backgroundPosX + 'px ' + backgroundPosY + 'px'
            });

            // Position the zoom window next to the cursor
            const zoomWindowLeft = offsetX + 20; // 20px offset from cursor
            const zoomWindowTop = offsetY + 20; // 20px offset from cursor

            // Ensure the zoom window stays within the bounds of the zoom container (optional, can be adjusted)
            const maxLeft = $(this).width() - zoomWindow.width();
            const maxTop = $(this).height() - zoomWindow.height();

            zoomWindow.css({
                'left': Math.min(Math.max(0, zoomWindowLeft), maxLeft) + 'px',
                'top': Math.min(Math.max(0, zoomWindowTop), maxTop) + 'px'
            });
        });

    });
    </script>
</body>

<!-- belle/product-layout-2.html   11 Nov 2019 12:42:40 GMT -->
</html>