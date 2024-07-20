<?php

session_start();

if (!isset($_SESSION["username"])) {
	header("Location: login.php");
}

include_once("koneksi.php");

$querytampil = "Select * from tb_mediumbus";
$queryalpha = "select * from tb_alpha where id_alpha = 'A1'";
$querysum = "select sum(d_aktual) from tb_mediumbus";
$pesan_error = "";

if (isset($_GET['pesan_sukses'])) {
	$pesan_sukses = $_GET['pesan_sukses'];
}

if (isset($_POST["submit"])) {
	if ($_POST["submit"] = "Ganti Alpha") {
		$id_alpha = htmlentities(strip_tags(trim($_POST['id_alpha'])));
		$n_alpha = htmlentities(strip_tags(trim($_POST['nilai_alpha'])));

		$pesan_error = "";

		if (empty($n_alpha)) {
			$pesan_error = "Nilai alpha belum di isi!";
		}

		if (($pesan_error === "") and ($_POST["submit"] = "Ganti Alpha")) {
			$id_alpha = mysqli_real_escape_string($koneksi, $id_alpha);
			$n_alpha = mysqli_real_escape_string($koneksi, $n_alpha);

			$queryupdatealpha = "update tb_alpha set nilai_alpha = '$n_alpha' where id_alpha = '$id_alpha'";

			$resultquery = mysqli_query($koneksi, $queryupdatealpha);

			if ($resultquery) {
				$pesan_sukses = "Alpha berhasil diupdate!<br>";
				$pesan_sukses = urlencode($pesan_sukses);
				header("Location: forecast_mediumbus.php?pesan_sukses={$pesan_sukses}");
			} else {
				die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
			}

			mysqli_free_result($resultquery);
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Forecasting</title>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<script src="js/chart.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">


</head>

<body>
	<!-- partial:index.partial.html -->
	<?php
	include_once 'navbar.php';
	?>

	<div>

		<div class="jumbotron">

			<!-- Main component for a primary marketing message or call to action -->
			<div class="container" style="height: fit-content; margin-top: 60px;">
				<h2 style="margin-bottom: 25px;">Data Peramalan Pelanggan Armada Medium Bus Periode Berikutnya</h2>
				<div style="margin-bottom: 10px;">
					<?php
					if ($pesan_error !== "") {
						echo "<div class='alert alert-danger alert-dismissible'>
						<a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<strong >Gagal!</strong> " . $pesan_error .
							"</div>";
					}

					if (isset($pesan_sukses)) {
						echo "<div class='alert alert-success alert-dismissible'>
						<a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<strong>Berhasil!</strong> " . $pesan_sukses .
							"</div>";
					}

					$resultquery = mysqli_query($koneksi, $queryalpha);

					if (!$resultquery) {
						die("Query Error : " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
					}

					$data_alpha = mysqli_fetch_assoc($resultquery);
					?>
					<div>
						<div>
							<h6 class="alert fs-3 text-success"> &alpha; = <b><?php echo $data_alpha['nilai_alpha']; ?></b></h6>
						</div>
						<div class="d-flex justify-content-between">
							<div>
								<form action="forecast_mediumbus.php" method="post" name="ubah_alpha">
									<div class="form-check form-check-inline d-flex">
										<div>
											<input class="form-control" type="text" name="nilai_alpha" placeholder="Edit Nilai Alpha" style="width:200px;">
											<input type="hidden" name="id_alpha" value="<?php echo $data_alpha['id_alpha']; ?>">
										</div>
										<div>

											<button class="btn btn-primary" name="submit" value="Ganti Alpha"><i class="bi bi-pencil-square"></i></button>
										</div>
										<!-- <input class="btn btn-primary" type="submit" name="submit" value="Ganti Alpha" > -->
									</div>
								</form>
							</div>
							<div class="dropdown">
								<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Armada MM Trans
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="forecast_bigbus.php">Big Bus</a>
									<a class="dropdown-item" href="forecast_mediumbus.php">Medium Bus</a>
									<a class="dropdown-item" href="forecast_longelf.php">Long Elf</a>
								</div>
							</div>
						</div>
					</div>

					<?php
					$id_alpha = $data_alpha["id_alpha"];
					$n_alpha = $data_alpha["nilai_alpha"];


					?>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<table class="table table-hover table-bordered">
							<tr>
								<th>Bulan - Tahun</th>
								<th>Data Aktual ($)</th>
								<th>Forecasting</th>
								<th>MAPE</th>
							</tr>
							<?php
							//untuk menentukan nilai peramalan pertama
							$resultquery1 = mysqli_query($koneksi, $querysum);
							$hasilsum = mysqli_fetch_row($resultquery1);

							$resultquery = mysqli_query($koneksi, $querytampil);
							$d_perkiraan = "";
							$count = mysqli_num_rows($resultquery);
							$loop = 0;
							$sum_abs_err = 0;
							$sum_abs_err2 = 0;
							$sum_abs_err_percent = 0;

							while ($row = mysqli_fetch_row($resultquery)) {


								//inisiasi data perkiraan pertama
								if ($d_perkiraan === "") {
									$d_perkiraan = $row[2];
								} else {
									$d_perkiraan = $h_perkiraan;
								}

								$array_perkiraan[] = $d_perkiraan;

								//rumus error
								// $error = $row[2]-$d_perkiraan;
								$error = abs(($row[2] - $d_perkiraan) / $row[2]) * 100;


								//rumus absolute error
								$abs_err = abs($error);
								$sum_abs_err = $sum_abs_err + $abs_err;

								//rumus absolute error pangkat 2
								$abs_err2 = pow($error, 2);
								$sum_abs_err2 = $sum_abs_err2 + $abs_err2;

								//rumus absolute error %
								$abs_err_percent = abs((($row[2] - $d_perkiraan) / $row[2]) * 100);
								$sum_abs_err_percent = $sum_abs_err_percent + $abs_err_percent;

								echo "<tr>";
								echo "<td>$row[1]</td>
								<td>$row[2]</td>
								<td>" . number_format($d_perkiraan, 2) . "</td>
								<td>" . number_format($error, 2) . "</td>";
								echo "</tr>";

								//rumus single exponential smoothing
								$h_perkiraan = $d_perkiraan + $n_alpha * ($row[2] - $d_perkiraan);

								//jika data sudah ditampilkan semua, lakukan peramalan untuk bulan berikutnya
								$loop = $loop + 1;
								if ($loop == $count) {
									echo "</table></div>";
									$d_aktual_next = $row[2];
									$d_perkiraan_next = $d_perkiraan;
									$d_ft = $d_perkiraan_next + $n_alpha * ($d_aktual_next - $d_perkiraan_next);

									//rumus MAPE
									$rata_abs_error_percent = $sum_abs_err_percent / $count;

									//rumus rata2 abs_err MAD
									$rataabs_err = $sum_abs_err / $count;

									//rumus rata2 abs_err2 MSD
									$rataabs_err2 = $sum_abs_err2 / $count;
							?>
									<div class="col-sm-3">
										<div class="card">
											<div class="card-header">
												<h5 style="margin-left: 20px;">MAPE : <?php echo number_format($rata_abs_error_percent, 3); ?></h5>
											</div>
										</div>
									</div>
									<h4 class="alert fs-2 text-success">Forecasting untuk periode berikutnya adalah <?php echo number_format($d_ft, 3); ?></h4>
							<?php
								}
							}
							?>

							<h2 style="margin-bottom: 25px; margin-top: 50px;">Grafik Peramalan Pelanggan Armada Medium Bus Periode Berikutnya</h2>
							<!-- Grafik -->
							<div class="t">
								<canvas id="speedChart"></canvas>
							</div>
							<script>
								var speedCanvas = document.getElementById("speedChart");

								Chart.defaults.global.defaultFontFamily = "Times New Roman";
								Chart.defaults.global.defaultFontSize = 15;

								var dataFirst = {
									label: "Aktual",
									data: [<?php
											$querydaktual = "select d_aktual from tb_mediumbus";
											$resultquery = mysqli_query($koneksi, $querydaktual);

											if (!$resultquery) {
												die("Query Error : " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
											}

											while ($data_aktual = mysqli_fetch_assoc($resultquery)) {
												echo "$data_aktual[d_aktual], ";
											}
											?>],

									lineTension: 0.3,
									fill: false,
									borderColor: '#FF7F50',
									backgroundColor: '#FF7F50',
									pointBorderColor: '#FF7F50',
									pointBackgroundColor: '#FF7F50',
									pointRadius: 5,
									pointHoverRadius: 15,
									pointHitRadius: 30,
									pointBorderWidth: 2,
									pointStyle: 'rect'
								};

								var dataSecond = {
									label: "Forecasting",
									data: [<?php
											foreach ($array_perkiraan as $arper) {
												echo "" . $arper . ", ";
											}
											echo "" . $d_ft . "";
											?>],

									lineTension: 0.3,
									fill: false,
									borderColor: '	#5F9EA0',
									backgroundColor: '	#5F9EA0',
									pointBorderColor: '	#5F9EA0',
									pointBackgroundColor: '	#5F9EA0',
									pointRadius: 5,
									pointHoverRadius: 15,
									pointHitRadius: 30,
									pointBorderWidth: 2
								};

								var speedData = {
									labels: [<?php
												$querybulan = "select bln_thn from tb_mediumbus";
												$resultquery = mysqli_query($koneksi, $querybulan);

												if (!$resultquery) {
													die("Query Error : " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
												}

												while ($data_bulan = mysqli_fetch_assoc($resultquery)) {
													echo "\"$data_bulan[bln_thn]\", ";
												}
												echo "\"Bulan berikutnya\"";
												?>],
									//labels: ["0s", "10s", "20s", "30s", "40s", "50s", "60s"],
									datasets: [dataFirst, dataSecond]
								};

								var chartOptions = {
									legend: {
										display: true,
										position: 'top',
										labels: {
											boxWidth: 80,
											fontColor: 'black'
										}
									}
								};

								var lineChart = new Chart(speedCanvas, {
									type: 'line',
									data: speedData,
									options: chartOptions
								});
							</script>
							<?php
							mysqli_free_result($resultquery);
							mysqli_close($koneksi);
							?>

					</div>

				</div>
			</div>
			<!-- partial -->
			<!-- <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script> -->
			<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>

</html>