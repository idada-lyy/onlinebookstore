<?php

@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};

$sql = "SELECT total_products, created_at, total_price FROM orders";

$select_orders = mysqli_query($conn, $sql) or die('query failed');

if (!$select_orders) {
    die('Query failed: ' . mysqli_error($conn));
}

$genreData = array();
$productData = array();
$yearlyData = array();
$monthlyData = array();
$weeklyData = array();
$finalWeeklyData = array();

while ($row = mysqli_fetch_assoc($select_orders)) {
    $decodeData = json_decode($row['total_products'], true);
    $decodeYearData = date("Y", strtotime($row['created_at']));
    $decodeMonthData = date("Y F", strtotime($row['created_at']));
    $previous7Days = date("Y-m-d", strtotime("-6 days"));

    for ($i = 10; $i >= 1; $i--) {
        $startDate = date("Y-m-d", strtotime("-" . ($i * 6) . " days")); // Go back i weeks
        $endDate = date("Y-m-d", strtotime("-" . (($i - 1) * 6) . " days")); // Go back i-1 weeks

        $weeklyData[$endDate] = 0;

        if ($row['created_at'] > $startDate && $row['created_at'] <= $endDate) {
            $weeklyData[$endDate] += $row['total_price'];
        }
    }

    foreach ($weeklyData as $key => $value) {
        if (array_key_exists($key, $finalWeeklyData)) {
            // If the key already exists in $finalWeeklyData, add the value to it
            $finalWeeklyData[$key] += $value;
        } else {
            // If the key doesn't exist in $finalWeeklyData, create it with the initial value
            $finalWeeklyData[$key] = $value;
        }
    }

    if (array_key_exists($decodeYearData, $yearlyData)) {
        $yearlyData[$decodeYearData] += $row['total_price'];
    } else {
        $yearlyData[$decodeYearData] = $row['total_price'];
    }

    if (array_key_exists($decodeMonthData, $monthlyData)) {
        $monthlyData[$decodeMonthData] += $row['total_price'];
    } else {
        $monthlyData[$decodeMonthData] = $row['total_price'];
    }

//    $totalAmount = 0;
//    for ($i = 1; $i < 10; $i++) {
//        $startDate = date("Y-m-d", strtotime("-" . ($i * 6) . " days")); // Go back i weeks
//        $endDate = date("Y-m-d", strtotime("-" . (($i - 1) * 6) . " days")); // Go back i-1 weeks
//
//        if ($row['created_at'] >= $startDate && $row['created_at'] < $endDate) {
//            $weeklyData[$startDate] += $row['total_price'];
//        } else{
//            $weeklyData[$startDate] = $totalAmount;
//        }
//
//
//    }

    foreach ($decodeData as $data) {

        if (array_key_exists($data['genre'], $genreData)) {
            $genreData[$data['genre']] += $data['quantity'];
        } else {
            $genreData[$data['genre']] = $data['quantity'];
        }
    }

    foreach ($decodeData as $data) {
        if (array_key_exists($data['name'], $productData)) {
            $productData[$data['name']] += $data['quantity'];
        } else {
            $productData[$data['name']] = $data['quantity'];
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <title>Stroyka</title>
    <link rel="icon" type="/image/png" href="../images/favicon.png"><!-- fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i"><!-- css -->
    <link rel="stylesheet" href="../vendor/bootstrap-4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/owl-carousel-2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/style.css"><!-- js -->
    <script src="../vendor/jquery-3.3.1/jquery.min.js"></script>
    <script src="../vendor/bootstrap-4.2.1/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/owl-carousel-2.3.4/owl.carousel.min.js"></script>
    <script src="../vendor/nouislider-12.1.0/nouislider.min.js"></script>
    <script src="../js/number.js"></script>
    <script src="../js/main.js"></script>
    <script src="../vendor/svg4everybody-2.1.9/svg4everybody.min.js"></script>
    <script>svg4everybody();</script><!-- font - fontawesome -->
    <link rel="stylesheet" href="../vendor/fontawesome-5.6.1/css/all.min.css"><!-- font - stroyka -->
    <link rel="stylesheet" href="../fonts/stroyka/stroyka.css">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-97489509-6"></script>
    <script>window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-97489509-6');</script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="site">
    <!---->
    <?php @include '../admin/header.php'; ?>

    <div class="site__body">
        <div class="page-header">
            <div class="page-header__container container">
                <div class="page-header__title">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>

        <div class="container ">
            <div class="row">
                <div class="col-md-12 pb-5">
                    <div class="card">
                        <div class="card-header">Sales by Yearly</div>
                        <div class="card-body">
                            <canvas id="periodChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 pb-5">
                    <div class="card">
                        <div class="card-header">Sales by Monthly</div>
                        <div class="card-body">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 pb-5">
                    <div class="card">
                        <div class="card-header">Sales by Weekly</div>
                        <div class="card-body">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 pb-5">
                    <div class="card">
                        <div class="card-header">Sales by Genre</div>
                        <div class="card-body">
                            <canvas id="genreChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 pb-5">
                    <div class="card">
                        <div class="card-header">Sales by Product</div>
                        <div class="card-body">
                            <canvas id="productChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var periodData = <?php echo json_encode($yearlyData); ?>;
    var periodChartCanvas = document.getElementById('periodChart').getContext('2d');
    new Chart(periodChartCanvas, {
        type: 'line',
        data: {
            labels: Object.keys(periodData),
            datasets: [{
                label: 'Sales by Yearly',
                data: Object.values(periodData),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    suggestedMax: 100,
                    ticks: {
                        // forces step size to be 50 units
                        stepSize: 10
                    }
                }
            }
        }
    });

    var periodData = <?php echo json_encode($monthlyData); ?>;
    var periodChartCanvas = document.getElementById('monthlyChart').getContext('2d');
    new Chart(periodChartCanvas, {
        type: 'line',
        data: {
            labels: Object.keys(periodData),
            datasets: [{
                label: 'Sales by Monthly',
                data: Object.values(periodData),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    suggestedMax: 100,
                    ticks: {
                        // forces step size to be 50 units
                        stepSize: 10
                    }
                }
            }
        }
    });

    var periodData = <?php echo json_encode($finalWeeklyData); ?>;
    var periodChartCanvas = document.getElementById('weeklyChart').getContext('2d');
    new Chart(periodChartCanvas, {
        type: 'line',
        data: {
            labels: Object.keys(periodData),
            datasets: [{
                label: 'Sales by Weekly',
                data: Object.values(periodData),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    suggestedMax: 100,
                    ticks: {
                        // forces step size to be 50 units
                        stepSize: 10
                    }
                }
            }
        }
    });

    // Sales by genre chart
    var genreData = <?php echo json_encode($genreData); ?>;
    var genreChartCanvas = document.getElementById('genreChart').getContext('2d');
    new Chart(genreChartCanvas, {
        type: 'line',
        data: {
            labels: Object.keys(genreData),
            datasets: [{
                label: 'Sales by Genre',
                data: Object.values(genreData),
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    suggestedMax: 100,
                    ticks: {
                        // forces step size to be 50 units
                        stepSize: 10
                    }
                }
            }
        }
    });

    // Sales by product chart
    var productData = <?php echo json_encode($productData); ?>;
    var productChartCanvas = document.getElementById('productChart').getContext('2d');
    new Chart(productChartCanvas, {
        type: 'line',
        data: {
            labels: Object.keys(productData),
            datasets: [{
                label: 'Sales by Product',
                data: Object.values(productData),
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    suggestedMax: 100,
                    ticks: {
                        // forces step size to be 50 units
                        stepSize: 10
                    }
                }
            }
        }
    });
</script>

</body>

</html>
