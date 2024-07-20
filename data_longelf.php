<?php

session_start();

if (!isset($_SESSION["username"])) {
	header("Location: login.php");
}

include_once("koneksi.php");

$querytampil = "Select * from tb_longelf";

if (isset($_GET['pesan_sukses'])) {
	$pesan_sukses = $_GET['pesan_sukses'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Data Pendapatan</title>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<!-- partial:index.partial.html -->
	<?php
	include_once 'navbar.php';
	?>

	<div>

		<div class="jumbotron">

			<!-- Main component for a primary marketing message or call to action -->
			<div class="container" style="height:fit-content; margin-top: 60px;">
				<h2 style="margin-bottom: 25px;">Data Long Elf</h2>
				<div class="d-flex justify-content-between my-4">
					<div class="dropdown">
						<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Armada MM Trans
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item" href="data_bigbus.php">Big Bus</a>
							<a class="dropdown-item" href="data_mediumbus.php">Medium Bus</a>
							<a class="dropdown-item" href="data_longelf.php">Long Elf</a>
						</div>
					</div>
					<a class="btn btn-primary" href="add_longelf.php">Tambah Data</a>
				</div>
				<table class="table table-hover table-bordered">
					<tr>
						<th>Periode - Tahun</th>
						<th>Data Aktual ($)</th>
						<th colspan="2">Action</th>
					</tr>
					<?php
					$resultquery = mysqli_query($koneksi, $querytampil);

					if (!$resultquery) {
						die("Query Error : " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
					}

					while ($data = mysqli_fetch_assoc($resultquery)) {
					?>
						<tr>
							<?php
							echo "<td>$data[bln_thn]</td>";
							echo "<td>$data[d_aktual]</td>";
							?>
							<td>
								<form action="edit_longelf.php" method="post">
									<input type="hidden" name="id" value="<?php echo "$data[id]"; ?>">
									<button class="btn btn-primary" name="submit" value="Edit"><i class="bi bi-pencil-square"></i></button>
									<!-- <input class="btn btn-primary" type="submit" name="submit" value="Edit"> -->
								</form>
							</td>
							<td>
								<form action="hapus_longelf.php" method="post">
									<input type="hidden" name="id" value="<?php echo "$data[id]"; ?>">
									<input type="hidden" name="bln_thn" value="<?php echo "$data[bln_thn]"; ?>">
									<button class="btn btn-danger" name="submit" value="Hapus"><i class="bi bi-trash3"></i></button>
									<!-- <input class="btn btn-danger" type="submit" name="submit" value="Hapus"> -->
								</form>
							</td>
						</tr>
					<?php

					}

					mysqli_free_result($resultquery);
					mysqli_close($koneksi);
					?>
				</table>
			</div>

		</div>
	</div>
	<!-- partial -->
	<!-- <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script> -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>