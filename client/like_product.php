<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "prodx_db");

// Check connection
if (!$conn) {
    die(json_encode(['success' => false, 'message' => 'Connection failed']));
}

// Get product ID from POST data
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($product_id > 0) {
    // Update likes count
    $query = "UPDATE products SET likes = likes + 1 WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Get updated likes count
        $query = "SELECT likes FROM products WHERE product_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        echo json_encode([
            'success' => true,
            'likes' => intval($row['likes'])
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update likes'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product ID'
    ]);
}

mysqli_close($conn);
?> 