<?php
session_start();
require_once '../prodx_db.php';

// Get user details if user_id is provided
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$seller = null;

if ($user_id > 0) {
    // Get seller details
    $query = "SELECT u.code_name, u.email, u.user_picture, u.contact_number 
              FROM users u 
              WHERE u.user_id = ? AND u.role = 2";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $seller = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Seller Information - proDX</title>
<meta name="description" content="Contact seller information">
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
<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Custom CSS -->
<style>
    body {
        background-color: #f8f9fa;
    }
    .contact-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
    }
    .contact-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        padding: 30px;
    }
    .seller-info {
        background: #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .seller-info h5 {
        color: #333;
        margin-bottom: 15px;
    }
    .seller-info p {
        margin-bottom: 8px;
        color: #555;
    }
    .contact-method {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .contact-method i {
        font-size: 24px;
        margin-right: 15px;
        color: #007bff;
    }
    .contact-method .details {
        flex-grow: 1;
    }
    .contact-method .label {
        font-weight: 500;
        color: #333;
        margin-bottom: 5px;
    }
    .contact-method .value {
        color: #666;
    }
    .seller-picture {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 20px;
        border: 3px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .seller-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .seller-header .seller-picture {
        width: 150px;
        height: 150px;
        margin: 0 auto 20px;
    }
</style>
</head>
<body class="template-collection">
<?php include 'includes/header.php'; ?>

    <!--Body Content-->
    <div id="page-content">
        <div class="contact-container">
            <div class="contact-card">
                <h2 class="text-center mb-4">Seller Information</h2>
                
                <?php if ($seller): ?>
                <div class="seller-header">
                    <?php if (!empty($seller['user_picture'])): ?>
                    <img src="<?php echo htmlspecialchars('../' . $seller['user_picture']); ?>" 
                         alt="<?php echo htmlspecialchars($seller['code_name']); ?>" 
                         class="seller-picture">
                    <?php else: ?>
                    <div class="seller-picture" style="background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user" style="font-size: 60px; color: #6c757d;"></i>
                    </div>
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($seller['code_name']); ?></h3>
                </div>

                <div class="seller-info">
                    <h5>Contact Information</h5>
                    <div class="contact-method">
                        <i class="fas fa-envelope"></i>
                        <div class="details">
                            <div class="label">Email Address</div>
                            <div class="value"><?php echo htmlspecialchars($seller['email']); ?></div>
                        </div>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-phone"></i>
                        <div class="details">
                            <div class="label">Contact Number</div>
                            <div class="value"><?php echo htmlspecialchars($seller['contact_number']); ?></div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-warning">
                    Seller information not found.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--End Body Content-->

<?php include 'includes/footer.php'; ?>

    <!--Scoll Top-->
    <span id="site-scroll"><i class="icon anm anm-angle-up-r"></i></span>
    <!--End Scoll Top-->
</body>
</html> 