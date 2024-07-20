<?php
session_start();

if (!isset($_SESSION["username"])) {
  header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>forecasting</title>
  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
  <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .masthead {
      background: url('img/bus2.png') no-repeat center center;
      background-size: auto;
      height: 100vh;
    }
  </style>
</head>

<body>
  <?php
  include_once 'navbar.php';
  ?>

  <div>
    <div class="">
      <!-- Main component for a primary marketing message or call to action -->
      <header class="bg-none text-white text-center">
        <div class="masthead container d-flex align-items-center flex-column">
          <!-- Masthead Avatar Image -->
          <img class="masthead-avatar mb-5" alt="" />
          <!-- Masthead Heading -->
          <h1 class="masthead-heading text-uppercase mt-5 mb-0" style="color: #FF7D29;">PREDIKSI JUMLAH PELANGGAN<br>RENTAL MOBIL MM TRANS</h1><br>
          <h3 style="color: orange;">Dengan Metode Single Eksponential Smoothing</h3>
          <!-- Icon Divider-->
          <div class="divider-custom divider-light">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
            <div class="divider-custom-line"></div>
          </div>
          <!-- Masthead Subheading-->
          <!-- <p class="masthead-subheading font-weight-light mb-0">Anandito Indrasmara Wijaya - 20500060 </p> -->
        </div>
      </header>
    </div>
  </div>
  <!-- partial -->
  <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</body>

</html>