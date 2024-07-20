<?php

session_start();

if (!isset($_SESSION["username"])) {
	header("Location: login.php");
}

include_once("koneksi.php");

$querytampil = "Select * from tb_admin";

if (isset($_GET['pesan_sukses'])) {
	$pesan_sukses = $_GET['pesan_sukses'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Admin</title>
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
			<div class="container" style="height: 75vh; margin-top: 60px;">
				<h2 style="margin-bottom: 25px;">Data Admin</h2>
				<?php
				if (isset($pesan_sukses)) {
					echo "<div class='alert alert-success alert-dismissible'>
						<a href='#'' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<strong>Berhasil!</strong> " . $pesan_sukses .
						"</div>";
				}
				?>
				<div style="margin-bottom: 10px;">
					<a class="btn btn-primary" href="tambah_admin.php">Tambah Admin</a>
				</div>
				<table class="table table-hover table-bordered">
					<tr>
						<th>Username</th>
						<th>Password</th>
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
							echo "<td>$data[username]</td>";
							echo "<td>$data[password]</td>";
							?>
							<td>
								<form action="edit_admin.php" method="post">
									<input type="hidden" name="id" value="<?php echo "$data[id]"; ?>">
									<button class="btn btn-primary" name="submit" value="Edit"><i class="bi bi-pencil-square"></i></button>
									<!-- <input class="btn btn-info" type="submit" name="submit" value="Edit"> -->
								</form>
							</td>
							<td>
								<form action="hapus_admin.php" method="post">
									<input type="hidden" name="id" value="<?php echo "$data[id]"; ?>">
									<input type="hidden" name="username" value="<?php echo "$data[username]"; ?>">
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
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</body>

</html>