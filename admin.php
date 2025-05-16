<?php 
// Load PHPMailer classes first
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Start session
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'prodx_db.php';

// Handle status update (Confirm or Reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['product_id'])) {
    $status = ($_POST['action'] === 'confirm') ? 2 : 3;
    $product_id = intval($_POST['product_id']);

    // Update product status
    $stmt = $conn->prepare("UPDATE products SET status = ? WHERE product_id = ?");
    $stmt->bind_param("ii", $status, $product_id);
    $stmt->execute();
    $stmt->close();

    // Fetch seller's email and info
    $stmt = $conn->prepare("
        SELECT p.product_name, u.email, u.code_name
        FROM products p
        JOIN users u ON p.user_id = u.user_id
        WHERE p.product_id = ?
    ");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($product_name, $seller_email, $code_name);
    $stmt->fetch();
    $stmt->close();

    // Load PHPMailer
    require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require 'vendor/phpmailer/phpmailer/src/SMTP.php';
    require 'vendor/phpmailer/phpmailer/src/Exception.php';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'eddiemarkbryandoverte@gmail.com'; // Your Gmail address
        $mail->Password   = 'uucx sptd lggg nnvl'; // Your app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('eddiemarkbryandoverte@gmail.com', 'Project Hiraya');
        $mail->addAddress($seller_email, $code_name);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Your Product Has Been " . ($status === 2 ? "Approved" : "Rejected");
        $mail->Body    = "
            <p>Hi <strong>$code_name</strong>,</p>
            <p>Your product <strong>$product_name</strong> has been <strong>" . ($status === 2 ? "approved" : "rejected") . "</strong>.</p>
            <p>Thank you for using Project Hiraya!</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
    }

    // Redirect after update
    header("Location: admin.php?updated=1");
    exit();
}
// Handle Create Company POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['company'])) {
    $companyName = trim($_POST['company']);
    $address = trim($_POST['address']);
    $emailAddress = trim($_POST['email_address']);
    $contactNumber = trim($_POST['contact_number']);

    if (!empty($companyName) && !empty($address) && !empty($emailAddress) && !empty($contactNumber)) {
        $stmt = $conn->prepare("INSERT INTO company (company, address, email_address, contact_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $companyName, $address, $emailAddress, $contactNumber);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php?company_added=1");
        exit();
    }
}

// Count product statuses
$statusCounts = [
    'published' => 0,
    'for_approval' => 0,
    'declined' => 0
];

$statusQuery = "SELECT status, COUNT(*) as total FROM products GROUP BY status";
$result = $conn->query($statusQuery);
while ($row = $result->fetch_assoc()) {
    switch ((int)$row['status']) {
        case 2:
            $statusCounts['published'] = (int)$row['total'];
            break;
        case 1:
            $statusCounts['for_approval'] = (int)$row['total'];
            break;
        case 0:
            $statusCounts['declined'] = (int)$row['total'];
            break;
    }
}

// Fetch existing companies
$companies = [];
$companyResult = $conn->query("SELECT id, company FROM company ORDER BY company ASC");
while ($row = $companyResult->fetch_assoc()) {
    $companies[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_company_id'])) {
    $companyId = intval($_POST['delete_company_id']);
    $stmt = $conn->prepare("DELETE FROM company WHERE id = ?");
    $stmt->bind_param("i", $companyId);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php?company_deleted=1");
    exit();
}



// Fetch product list
$query = "
    SELECT 
        p.product_id, 
        p.product_name, 
        p.status, 
        p.product_pictures,
        p.product_description,
        p.category,
        u.code_name
    FROM 
        products p
    LEFT JOIN 
        users u ON p.user_id = u.user_id
    WHERE 
        p.status = 1
    ORDER BY 
        p.product_id DESC
";


$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.php'); ?>
    <link rel="stylesheet" href="assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/png" href="assets/img/dost.png">
    
</head>
<body class="layout-4">
    <div class="page-loader-wrapper">
        <span class="loader"><span class="loader-inner"></span></span>
    </div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <?php include('includes/topnav.php'); ?>
            <?php include('includes/sidebar.php'); ?>

            <div class="main-content">
                <section class="section">
                    <div class="section-header d-flex justify-content-between align-items-center">
                        <h1>Products</h1>

                    </div>

                    <div class="row mb-4 justify-content-center" style="gap: 31px;">

<div class="col-md-3" style="min-width: 386px;">
    <div class="d-flex align-items-center bg-white rounded shadow-sm p-3 card-statistic-1" style="gap: 15px;">
        <div class="rounded bg-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
            <i class="fas fa-check text-white"></i>
        </div>
        <div>
            <div class="text-muted small">Published Products</div>
            <div class="fw-bold fs-5 text-dark"><?= $statusCounts['published'] ?></div>
        </div>
    </div>
</div>

<div class="col-md-3" style="min-width: 386px;">
    <div class="d-flex align-items-center bg-white rounded shadow-sm p-3 card-statistic-1" style="gap: 15px;">
        <div class="rounded bg-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
            <i class="fas fa-exclamation text-white"></i>
        </div>
        <div>
            <div class="text-muted small">For Approval</div>
            <div class="fw-bold fs-5 text-dark"><?= $statusCounts['for_approval'] ?></div>
        </div>
    </div>
</div>

<div class="col-md-3" style="min-width: 386px;">
    <div class="d-flex align-items-center bg-white rounded shadow-sm p-3 card-statistic-1" style="gap: 15px;">
        <div class="rounded bg-danger d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
            <i class="fas fa-thumbs-down text-white"></i>
        </div>
        <div>
            <div class="text-muted small">Declined</div>
            <div class="fw-bold fs-5 text-dark"><?= $statusCounts['declined'] ?></div>
        </div>
    </div>
</div>
</div>


                    <div class="section-body">
                        <?php if (isset($_GET['company_added'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Company created successfully!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>
<?php if (isset($_GET['company_deleted'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Company deleted successfully!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4>For Confirmation</h4>
                                        <button class="btn" style="background-color: #339498; color: white;" data-toggle="modal" data-target="#createCompanyModal">Create Company</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped v_center" id="table-1">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Account Name</th>
                                                    <th>Category</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // Status badge
                                                        switch ($row['status']) {
                                                            case 1: $statusBadge = '<div class="badge badge-warning">Pending</div>'; break;
                                                            case 2: $statusBadge = '<div class="badge badge-success">Confirmed</div>'; break;
                                                            case 3: $statusBadge = '<div class="badge badge-danger">Rejected</div>'; break;
                                                            default: $statusBadge = '<div class="badge badge-secondary">Unknown</div>';
                                                        }

                                                        // Category label
                                                        switch ($row['category']) {
                                                            case 1: $categoryLabel = 'Agriculture Product'; break;
                                                            case 2: $categoryLabel = 'Healthcare Product'; break;
                                                            case 3: $categoryLabel = 'Energy Product'; break;
                                                            default: $categoryLabel = 'Unknown';
                                                        }

                                                        echo "<tr>
                                                            <td>{$row['product_name']}</td>
                                                            <td>{$row['code_name']}</td>
                                                            <td>{$categoryLabel}</td>
                                                            <td>{$statusBadge}</td>
                                                            <td>
                                                                <button 
                                                                    class='btn btn-primary btn-detail' 
                                                                    data-id='{$row['product_id']}'
                                                                    data-name=\"" . htmlspecialchars($row['product_name'], ENT_QUOTES) . "\"
                                                                    data-description=\"" . htmlspecialchars($row['product_description'], ENT_QUOTES) . "\"
                                                                    data-pictures='" . htmlspecialchars($row['product_pictures'], ENT_QUOTES) . "'>
                                                                    Details
                                                                </button>
                                                            </td>
                                                        </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='5' class='text-center'>No products found</td></tr>";
                                                }

                                                $conn->close();
                                                ?>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" name="product_id" id="modalProductId">
                    
                    <!-- Image viewer container -->
                    <div id="imageViewer" style="position: relative; max-width: 100%; margin-bottom: 15px;">
                        <img id="modalProductImage" src="" alt="Product Image" style="display: none; width: 100%; max-height: 300px; object-fit: contain; border-radius: 8px;">
                        
                        <!-- Floating Prev and Next buttons -->
                        <button type="button" id="prevImage" class="btn btn-light" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); background-color: rgba(0,0,0,0.4); color: white; border: none; display: none; font-size: 1.5rem; padding: 5px 10px; border-radius: 50%;">⟨</button>
                        <button type="button" id="nextImage" class="btn btn-light" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); background-color: rgba(0,0,0,0.4); color: white; border: none; display: none; font-size: 1.5rem; padding: 5px 10px; border-radius: 50%;">⟩</button>
                    </div>

                    <h5 id="modalProductName"></h5>
                    <p id="modalProductDescription" class="text-justify"></p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnReject">Reject ❌</button>
                <button type="button" class="btn btn-success" id="btnConfirm">Confirm ✔️</button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- Processing Modal -->
<div class="modal fade" id="processingModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content text-center p-3">
      <div class="spinner-border text-primary mb-2" role="status"></div>
      <p>Processing...</p>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content text-center p-3">
      <div class="text-success display-4 mb-2">✔️</div>
      <p>Successfully updated!</p>
    </div>
  </div>
</div>

<!-- Create Company Modal -->
<div class="modal fade" id="createCompanyModal" tabindex="-1" role="dialog" aria-labelledby="createCompanyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <!-- Main Form: For Creating a Company -->
      <form method="POST" id="createCompanyForm">
        <div class="modal-header">
          <h5 class="modal-title">Create Company</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
            <!-- Left Column: Form Inputs -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="companyName">Company Name</label>
                <input type="text" name="company" id="companyName" class="form-control" required>
              </div>

              <div class="form-group">
                <label for="companyAddress">Address</label>
                <input type="text" name="address" id="companyAddress" class="form-control" required>
              </div>

              <div class="form-group">
                <label for="companyEmail">Email</label>
                <input type="email" name="email_address" id="companyEmail" class="form-control" required>
              </div>

              <div class="form-group">
                <label for="companyContact">Contact Number</label>
                <input type="text" name="contact_number" id="companyContact" class="form-control" required>
              </div>
            </div>

            <!-- Right Column: Company List (OUTSIDE form below) -->
            <div class="col-md-6">
              <h6>Existing Companies</h6>
              <input type="text" class="form-control mb-2" id="companySearch" placeholder="Search company...">
              <ul class="list-group" id="companyList" style="max-height: 300px; overflow-y: auto;">
                <!-- We'll render the delete forms outside this main form -->
              </ul>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>

      <!-- Render the company list outside the form to avoid nesting -->
      <script>
        document.addEventListener("DOMContentLoaded", function () {
          const companies = <?= json_encode($companies) ?>;
          const companyList = document.getElementById("companyList");

          companies.forEach(company => {
            const li = document.createElement("li");
            li.className = "list-group-item d-flex justify-content-between align-items-center company-item";

            const span = document.createElement("span");
            span.className = "company-name";
            span.textContent = company.company;

            const form = document.createElement("form");
            form.method = "POST";
            form.className = "ml-2 d-inline";
            form.action = ""; // Set your actual delete handler if needed
            form.onsubmit = () => confirm("Are you sure you want to delete this company?");

            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "delete_company_id";
            input.value = company.id;

            const button = document.createElement("button");
            button.type = "submit";
            button.className = "btn btn-sm btn-danger";
            button.setAttribute("formnovalidate", true);
            button.innerHTML = "&times;";

            form.appendChild(input);
            form.appendChild(button);

            li.appendChild(span);
            li.appendChild(form);
            companyList.appendChild(li);
          });
        });
      </script>

    </div>
  </div>
</div>







    <!-- JS Assets -->
    <script src="assets/bundles/lib.vendor.bundle.js"></script>
    <script src="js/CodiePie.js"></script>
    <script src="assets/modules/datatables/datatables.min.js"></script>
    <script src="assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
    <script src="assets/modules/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/modules/sweetalert/sweetalert.min.js"></script>
    <script src="js/page/modules-datatables.js"></script>
    <script src="js/page/modules-sweetalert.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/custom.js"></script>
    <script src="assets/modules/jquery.min.js"></script>
    <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>


    <script>
 document.querySelectorAll('.btn-detail').forEach(button => {
    button.addEventListener('click', function () {
        const img = document.getElementById('modalProductImage');
        const prevBtn = document.getElementById('prevImage');
        const nextBtn = document.getElementById('nextImage');
        
        let pictures = [];
        try {
            pictures = JSON.parse(this.getAttribute('data-pictures')); // Correct: data-pictures
        } catch (e) {
            pictures = [];
        }

        let currentImageIndex = 0;

        function updateImageView() {
            if (pictures.length > 0) {
                img.src = pictures[currentImageIndex]; // Set the current image
                img.style.display = 'block';

                // Show buttons only if there’s more than one image
                prevBtn.style.display = pictures.length > 1 && currentImageIndex > 0 ? 'block' : 'none';
                nextBtn.style.display = pictures.length > 1 && currentImageIndex < pictures.length - 1 ? 'block' : 'none';
            } else {
                img.style.display = 'none';
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            }
        }

        updateImageView();

        // Set product details in modal
        document.getElementById('modalProductId').value = this.getAttribute('data-id');
        document.getElementById('modalProductName').textContent = this.getAttribute('data-name');
        document.getElementById('modalProductDescription').textContent = this.getAttribute('data-description');

        $('#detailsModal').modal('show');

        // Handle Prev button click
        prevBtn.onclick = () => {
            if (currentImageIndex > 0) {
                currentImageIndex--;
                updateImageView();
            }
        };

        // Handle Next button click
        nextBtn.onclick = () => {
            if (currentImageIndex < pictures.length - 1) {
                currentImageIndex++;
                updateImageView();
            }
        };
    });
});
    </script>
    <script>
document.getElementById('btnConfirm').addEventListener('click', () => {
    processAction('confirm');
});
document.getElementById('btnReject').addEventListener('click', () => {
    processAction('reject');
});

function processAction(action) {
    // Show processing modal
    $('#detailsModal').modal('hide');
    $('#processingModal').modal('show');

    setTimeout(() => {
        // Set hidden form field and submit
        const form = document.querySelector('#detailsModal form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'action';
        hiddenInput.value = action;
        form.appendChild(hiddenInput);
        form.submit();
    }, 1000); // 1 second delay before form submission
}
</script>
<script>
  document.getElementById('companySearch').addEventListener('keyup', function () {
    const searchValue = this.value.toLowerCase();
    const items = document.querySelectorAll('.company-item');

    items.forEach(item => {
      const name = item.querySelector('.company-name').textContent.toLowerCase();
      item.style.display = name.includes(searchValue) ? '' : 'none';
    });
  });
</script>


</body>
</html>
