<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: logout.php");
    exit();
}
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0"); 
include 'db_connection.php';
$studentsQuery = "SELECT COUNT(*) AS total_students FROM etudiant";
$studentsResult = $conn->query($studentsQuery);
$studentsRow = $studentsResult->fetch_assoc();
$totalStudents = $studentsRow['total_students'];
$todayDate = date('Y-m-d');
$studentsAteQuery = "SELECT COUNT(DISTINCT command.etudiant_id) AS students_ate_today 
                     FROM command 
                     WHERE date = '$todayDate' AND done = 1";
$studentsAteResult = $conn->query($studentsAteQuery);
$studentsAteRow = $studentsAteResult->fetch_assoc();
$studentsAteToday = $studentsAteRow['students_ate_today'];
$categoryQuery = "SELECT products.nom, COUNT(contains.product_id) AS count 
                  FROM contains 
                  JOIN command ON contains.command_id = command.id 
                  JOIN products ON contains.product_id = products.id 
                  JOIN categories ON products.category_id = categories.id 
                  WHERE command.date = '$todayDate' AND categories.nom = 'Principal Food' 
                  GROUP BY products.nom";
$categoryResult = $conn->query($categoryQuery);
$categoryData = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categoryData[] = ['name' => $row['nom'], 'count' => $row['count']];
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex,nofollow" />
    <title>1337 Restaurant</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/ample-admin-lite/" />
    <link rel="icon" type="image/png" sizes="16x16" href="plugins/images/favicon.png" />
    <link href="plugins/bower_components/chartist/dist/chartist.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css" />
    <link href="css/style.min.css" rel="stylesheet" />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
            font-size: 16px;
            font-weight: normal;
        }
        .container-fluid {
            display: flex;
            height: 100vh;
            flex-direction: column;
         }
    </style>
  </head>
  <body>
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
      <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
          <div class="navbar-header" data-logobg="skin6">
            <a class="navbar-brand" href="dashboard.php">
              <b class="logo-icon">
                <img src="plugins/images/logo-icon.png" alt="homepage" />
              </b>
              <span class="logo-text">
                <img src="plugins/images/logo-text.png" alt="homepage" />
              </span>
            </a>
            <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
          </div>
          <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
              <li>
                <span class="text-white font-medium">Bonjour <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
              </li>
              </ul>
          </div>
        </nav>
      </header>
      <aside class="left-sidebar" data-sidebarbg="skin6">
        <div class="scroll-sidebar">
          <nav class="sidebar-nav">
            <ul id="sidebarnav">
              <li class="sidebar-item pt-2">
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="dashboard.php" aria-expanded="false">
                  <i class="far fa-clock" aria-hidden="true"></i>
                  <span class="hide-menu">Dashboard</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="TodayMenu.php" aria-expanded="false">
                  <i class="fa fa-columns" aria-hidden="true"></i>
                  <span class="hide-menu">Today Menu</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="Commands.php" aria-expanded="false">
                  <i class="fa fa-table" aria-hidden="true"></i>
                  <span class="hide-menu">Commands</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="Add_product_page.php" aria-expanded="false">
                  <i class="fa fa-columns" aria-hidden="true"></i>
                  <span class="hide-menu">Add Product</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="Logoutss.php" aria-expanded="false">
                  <i class="fas fa-arrow-alt-circle-right" aria-hidden="true"></i>
                  <span class="hide-menu">Logout</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </aside>
      <div class="page-wrapper">
        <div class="page-breadcrumb bg-white">
          <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
              <h4 class="page-title">Dashboard</h4>
            </div>
          </div>
        </div>
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
              <div class="card text-center">
                <div class="card-body">
                  <h5 class="card-title">Total Students</h5>
                  <p class="card-text"><?php echo $totalStudents; ?></p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="card text-center">
                <div class="card-body">
                  <h5 class="card-title">Students Ate Today</h5>
                  <p class="card-text"><?php echo $studentsAteToday; ?></p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="card text-center">
                <div class="card-body">
                  <h5 class="card-title">Principal Food Choices</h5>
                  <canvas id="foodChoicesChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <footer class="footer text-center">
          2024 © 1337 Restaurant
        </footer>
      </div>
    </div>
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app-style-switcher.js"></script>
    <script src="plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="js/waves.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/custom.js"></script>
    <script src="plugins/bower_components/chartist/dist/chartist.min.js"></script>
    <script src="plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          var ctx = document.getElementById('foodChoicesChart').getContext('2d');
          var foodChoicesChart = new Chart(ctx, {
              type: 'pie',
              data: {
                  labels: <?php echo json_encode(array_column($categoryData, 'name')); ?>,
                  datasets: [{
                      data: <?php echo json_encode(array_column($categoryData, 'count')); ?>,
                      backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe'],
                      hoverBackgroundColor: ['#ff6384', '#36a2eb', '#cc65fe']
                  }]
              },
              options: {
                  responsive: true,
                  plugins: {
                      legend: {
                          position: 'top',
                      }
                  }
              }
          });
      });
    </script>
  </body>
</html>

