<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
}

include_once("koneksi.php");

$querytampil = "SELECT * FROM tb_bigbus";
$queryalpha = "SELECT * FROM tb_alpha WHERE id_alpha = 'A1'";
$querysum = "SELECT SUM(d_aktual) FROM tb_bigbus";
$pesan_error = "";

if (isset($_GET['pesan_sukses'])) {
    $pesan_sukses = $_GET['pesan_sukses'];
}

if (isset($_POST["submit"])) {
    if ($_POST["submit"] === "Ganti Alpha") {
        $id_alpha = htmlentities(strip_tags(trim($_POST['id_alpha'])));
        $n_alpha = htmlentities(strip_tags(trim($_POST['nilai_alpha'])));

        $pesan_error = "";

        if (empty($n_alpha)) {
            $pesan_error = "Nilai alpha belum di isi!";
        }

        if (($pesan_error === "") && ($_POST["submit"] === "Ganti Alpha")) {
            $id_alpha = mysqli_real_escape_string($koneksi, $id_alpha);
            $n_alpha = mysqli_real_escape_string($koneksi, $n_alpha);

            $queryupdatealpha = "UPDATE tb_alpha SET nilai_alpha = '$n_alpha' WHERE id_alpha = '$id_alpha'";

            $resultquery = mysqli_query($koneksi, $queryupdatealpha);

            if ($resultquery) {
                $pesan_sukses = "Alpha berhasil diupdate!<br>";
                $pesan_sukses = urlencode($pesan_sukses);
                header("Location: forecast_bigbus.php?pesan_sukses={$pesan_sukses}");
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
    <?php
    include_once 'navbar.php';
    ?>

    <div>
        <div class="jumbotron">
            <div class="container" style="height: fit-content; margin-top: 60px;">
                <h2 style="margin-bottom: 25px;">Data Peramalan Pelanggan Armada Big Bus Periode Berikutnya</h2>

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
                                <form action="forecast_bigbus.php" method="post" name="ubah_alpha">
                                    <div class="form-check form-check-inline d-flex">
                                        <div>
                                            <input class="form-control" type="text" name="nilai_alpha" placeholder="Edit Nilai Alpha" style="width:200px;">
                                            <input type="hidden" name="id_alpha" value="<?php echo $data_alpha['id_alpha']; ?>">
                                        </div>
                                        <div>
                                            <button class="btn btn-primary" name="submit" value="Ganti Alpha"><i class="bi bi-pencil-square"></i></button>
                                        </div>
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
                                <?php
                                for ($i = 1; $i <= 9; $i++) {
                                    echo "<th>&alpha; = 0." . $i . "</th>";
                                }
                                ?>
                            </tr>
                            <?php
                            $resultquery1 = mysqli_query($koneksi, $querysum);
                            $hasilsum = mysqli_fetch_row($resultquery1);

                            $resultquery = mysqli_query($koneksi, $querytampil);
                            $array_perkiraan = array_fill(1, 9, "");
                            $array_perkiraan_sebelumnya = array_fill(1, 9, "");
                            $total_mape = array_fill(1, 9, 0);
                            $total_mse = array_fill(1, 9, 0); // Initialize MSE array
                            $mape_values = array_fill(1, 9, []); // Initialize array to store MAPE values for each period
                            $count = mysqli_num_rows($resultquery);
                            $loop = 0;
                            $total_months = 0;

                            while ($row = mysqli_fetch_row($resultquery)) {
                                echo "<tr>";
                                echo "<td>$row[1]</td><td>$row[2]</td>";

                                for ($i = 1; $i <= 9; $i++) {
                                    $n_alpha = 0.1 * $i;

                                    if ($array_perkiraan[$i] === "") {
                                        $array_perkiraan[$i] = $row[2];
                                    } else {
                                        $array_perkiraan_sebelumnya[$i] = $array_perkiraan[$i];
                                        $array_perkiraan[$i] = $array_perkiraan[$i] + $n_alpha * ($row[2] - $array_perkiraan[$i]);

                                        // Calculate MAPE for the current month
                                        $mape = abs(($row[2] - $array_perkiraan_sebelumnya[$i]) / $row[2]) * 100;
                                        $total_mape[$i] += $mape;
                                        $mape_values[$i][] = $mape; // Store MAPE value for this period

                                        // Calculate MSE for the current month
                                        $mse = pow(($row[2] - $array_perkiraan_sebelumnya[$i]), 2);
                                        $total_mse[$i] += $mse;
                                    }

                                    echo "<td>" . ($loop === 0 ? '-' : number_format($array_perkiraan_sebelumnya[$i], 2)) . "</td>";
                                }

                                echo "</tr>";
                                $loop++;
                                $total_months++;
                            }

                            // Display predictions for the next month and average MAPE
                            echo "<tr><td>Bulan berikutnya</td><td>-</td>";

                            for ($i = 1; $i <= 9; $i++) {
                                echo "<td>" . number_format($array_perkiraan[$i], 2) . "</td>";
                            }

                            echo "</tr>";

                            // Display average MAPE
                            echo "<tr><td>MAPE</td><td>-</td>";

                            for ($i = 1; $i <= 9; $i++) {
                                $average_mape = ($total_months !== 0) ? $total_mape[$i] / ($total_months - 1) : 0;
                                echo "<td>" . number_format($average_mape, 2) . " %</td>";
                            }

                            echo "</tr>";

                            // Display average MSE
                            echo "<tr><td>MSE</td><td>-</td>";

                            for ($i = 1; $i <= 9; $i++) {
                                $average_mse = ($total_months !== 0) ? $total_mse[$i] / ($total_months - 1) : 0;
                                echo "<td>" . number_format($average_mse, 2) . "</td>";
                            }

                            echo "</tr>";

                            echo "</tr>";
                            ?>
                        </table>

						<?php
// Initialize variables to find the alpha with the smallest MAPE
$min_mape = PHP_INT_MAX;
$best_alpha = null;
$best_alpha_index = null;

// Loop through each alpha value
for ($i = 1; $i <= 9; $i++) {
    $n_alpha = 0.1 * $i;

    // Reset arrays for each alpha
    $array_perkiraan = array_fill(1, 9, "");
    $array_perkiraan_sebelumnya = array_fill(1, 9, "");
    $total_mape = array_fill(1, 9, 0);
    $count = 0;

    // Calculate MAPE for each alpha
    $resultquery = mysqli_query($koneksi, $querytampil);
    while ($row = mysqli_fetch_row($resultquery)) {
        if ($array_perkiraan[$i] === "") {
            $array_perkiraan[$i] = $row[2];
        } else {
            $array_perkiraan_sebelumnya[$i] = $array_perkiraan[$i];
            $array_perkiraan[$i] = $array_perkiraan[$i] + $n_alpha * ($row[2] - $array_perkiraan[$i]);

            // Calculate MAPE for the current month
            $mape = abs(($row[2] - $array_perkiraan_sebelumnya[$i]) / $row[2]) * 100;
            $total_mape[$i] += $mape;
        }
        $count++;
    }

    // Calculate average MAPE for the current alpha
    $average_mape = ($count !== 0) ? $total_mape[$i] / $count : 0;

    // Find the alpha with the smallest MAPE
    if ($average_mape < $min_mape) {
        $min_mape = $average_mape;
        $best_alpha = $n_alpha;
        $best_alpha_index = $i;
    }
}

// Prepare data for the best alpha
$array_perkiraan = array_fill(1, 9, "");
$array_perkiraan_sebelumnya = array_fill(1, 9, "");
$best_alpha_data = [];
$resultquery = mysqli_query($koneksi, $querytampil);
while ($row = mysqli_fetch_row($resultquery)) {
    if ($array_perkiraan[$best_alpha_index] === "") {
        $array_perkiraan[$best_alpha_index] = $row[2];
    } else {
        $array_perkiraan_sebelumnya[$best_alpha_index] = $array_perkiraan[$best_alpha_index];
        $array_perkiraan[$best_alpha_index] = $array_perkiraan[$best_alpha_index] + $best_alpha * ($row[2] - $array_perkiraan[$best_alpha_index]);
    }
    $best_alpha_data[] = [
        'month' => $row[1],
        'actual' => $row[2],
        'forecast' => $array_perkiraan_sebelumnya[$best_alpha_index]
    ];
}
?>
<div class="container">
    <div class="jumbotron mt-4">
        <!-- Existing content -->

        <!-- New section for displaying the chart -->
        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-center">Grafik Data Aktual dan Prediksi dengan Alpha MAPE Terkecil</h3>
                <canvas id="forecastChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Data from PHP
    var bestAlphaData = <?php echo json_encode($best_alpha_data); ?>;

    var labels = bestAlphaData.map(function(e) { return e.month; });
    var actualData = bestAlphaData.map(function(e) { return e.actual; });
    var forecastData = bestAlphaData.map(function(e) { return e.forecast; });

    var ctx = document.getElementById('forecastChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Data Aktual',
                data: actualData,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: false
            }, {
                label: 'Data Prediksi',
                data: forecastData,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: false
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>



                    </div>
                </div>
            </div>
        </div>
    </div>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>


<?php
mysqli_free_result($resultquery);
mysqli_close($koneksi);
?>


