<!--Footer-->
    <footer id="footer">
        <div class="site-footer">
        	<div class="container">
     			<!--Footer Links-->
     			<!--Footer Links-->
                 <div class="footer-top">
                	<div class="row">
                    	<div class="col-12 col-sm-12 col-md-6 col-lg-6 footer-links">
                        	<h4 class="h4">Informations</h4>
                            <ul>
                            	<li><a href="#">About us</a></li>
                                <li><a href="#">Contact Us</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 footer-links">
                        	<h4 class="h4">Customer Services</h4>
                            <ul>
                            	<li><a href="#">Request Personal Data</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--End Footer Links-->
                <hr>
                <div class="footer-bottom">
                	<div class="row">
                    	<!--Footer Copyright-->
                        <!--End Footer Copyright-->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--End Footer-->
    <!--Scoll Top-->
    <span id="site-scroll"><i class="icon anm anm-angle-up-r"></i></span>
    <!--End Scoll Top-->
    

    
        
     <!-- Including Jquery -->
     <script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
     <script src="assets/js/vendor/jquery.cookie.js"></script>
     <script src="assets/js/vendor/modernizr-3.6.0.min.js"></script>
     <script src="assets/js/vendor/wow.min.js"></script>
     <!-- Including Javascript -->
     <script src="assets/js/bootstrap.min.js"></script>
     <script src="assets/js/plugins.js"></script>
     <script src="assets/js/popper.min.js"></script>
     <script src="assets/js/lazysizes.js"></script>
     <script src="assets/js/main.js"></script>
     <!-- Photoswipe Gallery -->
     <script src="assets/js/vendor/photoswipe.min.js"></script>
     <script src="assets/js/vendor/photoswipe-ui-default.min.js"></script>
     <script>
        $(function(){
            var $pswp = $('.pswp')[0],
                image = [],
                getItems = function() {
                    var items = [];
                    $('.lightboximages a').each(function() {
                        var $href   = $(this).attr('href'),
                            $size   = $(this).data('size').split('x'),
                            item = {
                                src : $href,
                                w: $size[0],
                                h: $size[1]
                            }
                            items.push(item);
                    });
                    return items;
                }
            var items = getItems();
        
            $.each(items, function(index, value) {
                image[index]     = new Image();
                image[index].src = value['src'];
            });
            $('.prlightbox').on('click', function (event) {
                event.preventDefault();
              
                var $index = $(".active-thumb").parent().attr('data-slick-index');
                $index++;
                $index = $index-1;
        
                var options = {
                    index: $index,
                    bgOpacity: 0.9,
                    showHideOpacity: true
                }
                var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
                lightBox.init();
            });
        });
        </script>
    </div>
    </div>

	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        	<div class="pswp__bg"></div>
            <div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button><button class="pswp__button pswp__button--share" title="Share"></button><button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button><button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>

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
                // Compare absolute URLs
                if (tempImage.src === currentImageSrcForZoomAbsolute) {
                    naturalImageWidth = this.naturalWidth;
                    naturalImageHeight = this.naturalHeight;

                    // Get and store rendered dimensions of the active image element
                    const activeImageElement = mainImage.closest('.product-image-item.active').find('img');
                    renderedImageWidth = activeImageElement.width();
                    renderedImageHeight = activeImageElement.height();

                    // Trigger a mousemove to update the background position immediately
                    // if (zoomContainer.is(':hover')) {
                         zoomContainer.trigger('mousemove');
                    // }
                } else {
                     // console.log('Loaded image is not the current active image for zoom.', tempImage.src, currentImageSrcForZoomAbsolute); // Debugging log;
                }
            };
            tempImage.onerror = function() {
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