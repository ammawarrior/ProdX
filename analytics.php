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

// Handle selected year (default: current year)
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Fetch approved products per month for selected year
$approvedPerMonth = array_fill(0, 12, 0); // Initialize 12 months

$query = "
    SELECT MONTH(created_at) AS month, COUNT(*) AS count
    FROM products
    WHERE status = 2 AND YEAR(created_at) = ?
    GROUP BY MONTH(created_at)
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $selectedYear);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $approvedPerMonth[(int)$row['month'] - 1] = (int)$row['count'];
}
$stmt->close();

// Get total approved for current month (only if selected year == current year)
$currentMonthApproved = 0;
if ($selectedYear == date('Y')) {
    $currentMonthApproved = $approvedPerMonth[(int)date('n') - 1];
}

// Generate year options (from earliest year in DB to current year)
$yearOptions = [];
$res = $conn->query("SELECT MIN(YEAR(created_at)) as min_year FROM products");
if ($row = $res->fetch_assoc()) {
    $minYear = $row['min_year'] ?? date('Y');
    for ($y = $minYear; $y <= date('Y'); $y++) {
        $yearOptions[] = $y;
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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/header.php'); ?>
    <link rel="stylesheet" href="assets/modules/chart.min.css">
    <link rel="icon" type="image/png" href="assets/img/dost.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .card-statistic-1 {
    transition: transform 0.2s ease;
}
.card-statistic-1:hover {
    transform: scale(1.02);
}
select.form-select {
    min-width: 120px;
}
.year-filter-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        font-family: Arial, sans-serif;
    }

    .year-filter-label {
        font-size: 16px;
        font-weight: 500;
        color: #333;
    }

    .year-filter-select {
        padding: 8px 16px;
        font-size: 16px;
        border: 2px solid #339498;
        border-radius: 999px;
        background-color: #f9f9f9;
        color: #333;
        cursor: pointer;
        transition: border-color 0.2s, background-color 0.2s;
    }

    .year-filter-select:hover,
    .year-filter-select:focus {
    border-color: #339498 !important;
    background-color: #eaffea !important;
    box-shadow: 0 0 0 3px rgba(71, 195, 99, 0.4) !important; /* green glow */
    outline: none !important;
}


    </style>
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
        <div class="section-header mb-4">
            <h1>Product Submission Analytics</h1>
        </div>
        <div class="row mb-4 justify-content-center" style="gap: 30px;">

    <div class="col-md-3" style="min-width: 385px;">
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

    <div class="col-md-3" style="min-width: 385px;">
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

    <div class="col-md-3" style="min-width: 385px;">
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

 

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        
                    <div class="card-header bg-success text-white rounded-top d-flex justify-between align-center" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
    <h5 class="mb-0">Number of Submissions Over Time</h5>
    <form method="GET" class="year-form" style="display: flex; align-items: center; gap: 10px;">
        <label for="year" class="year-filter-label" style="color: white; margin-bottom: 0;">Select Year:</label>
        <select name="year" id="year" onchange="this.form.submit()" class="year-filter-select">
            <?php foreach ($yearOptions as $year): ?>
                <option value="<?= $year ?>" <?= $year == $selectedYear ? 'selected' : '' ?>>
                    <?= $year ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>


                        <div class="card-body">
                            <div id="apex-timeline-chart"></div>
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

    <!-- JS Assets -->
    <script src="assets/bundles/lib.vendor.bundle.js"></script>
    <script src="js/CodiePie.js"></script>
    <script src="assets/modules/chart.min.js"></script>
    <script src="js/scripts.js"></script>
    <script>
    var options = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '45%',
                endingShape: 'rounded'
            }
        },
        dataLabels: {
            enabled: false
        },
        series: [{
            name: 'Approved Products',
            data: <?= json_encode($approvedPerMonth) ?>
        }],
        xaxis: {
            categories: [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ],
            labels: {
                style: {
                    fontSize: '13px'
                }
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            min: 0,
            forceNiceScale: true,
            decimalsInFloat: 0,
            labels: {
                style: {
                    fontSize: '13px'
                }
            }
        },
        grid: {
            strokeDashArray: 4,
            xaxis: { lines: { show: false } },
            yaxis: { lines: { show: true } }
        },
        colors: ['#339498']
    };

    var chart = new ApexCharts(document.querySelector("#apex-timeline-chart"), options);
    chart.render();
</script>




</body>
</html>
