<?php
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kehadiran Kelab E-sport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_guru.php"); ?>
<main>
    <?php
    if (isset($_POST['submit'])) {
        $idAktiviti = $_POST['idAktiviti'] ?? '';
        $idAhli = $_POST['idAhli'] ?? '';
        $pilih = $_POST['pilih'];

        if ($pilih == 1) {
            $sql = "SELECT * FROM kehadiran
                    JOIN aktiviti ON kehadiran.idAktiviti = aktiviti.idAktiviti
                    JOIN ahli ON kehadiran.idAhli = ahli.idAhli
                    WHERE kehadiran.idAktiviti = '$idAktiviti'";

            $result = mysqli_query($conn, $sql);
            $kehadiran = mysqli_fetch_array($result);
            $bil_rekod = mysqli_num_rows($result);

            if ($bil_rekod > 0) {
                $Lokasi = $kehadiran["Lokasi"];
                $Tajuk = $kehadiran["Tajuk"];
                $Tarikh = date_format(date_create($kehadiran['Tarikh']), 'd-m-Y');
                $Masa = date_format(date_create($kehadiran['Masa']), 'h:i A');

                echo "<div class='laporan'>
                        <h3 class='Tajuk'>Senarai Nama Kehadiran Mengikut Aktiviti</h3>
                        <div class='details'>
                            <div>Aktiviti: $Tajuk</div>
                            <div>Tempat: $Lokasi</div>
                            <div>Tarikh: $Tarikh</div>
                            <div>Masa: $Masa</div>
                        </div>
                      </div>";

                echo "<table class='senarai'>
                        <tr>
                            <th>idAhli</th>
                            <th>Nama</th>
                            <th>Hadir</th>
                        </tr>";

                do {
                    echo "<tr>
                            <td>{$kehadiran['idAhli']}</td>
                            <td class='nama'>{$kehadiran['Nama']}</td>
                            <td>";
                    if ($kehadiran['Status'] == "Hadir") {
                        echo "<span class='status-yes'>&#10004;</span>";
                    } else if ($kehadiran['Status'] == "Tidak Hadir") {
                        echo "<span class='status-no'>&#10008;</span>";
                    }
                    echo "</td></tr>";
                } while ($kehadiran = mysqli_fetch_array($result));

                echo "</table>";
            } else {
                echo "<h3 style='text-align:center;margin-top:100px;color:red;'>Tiada Senarai Laporan Bagi Aktiviti</h3>";
            }
        } else if ($pilih == 2) {
            $sql = "SELECT * FROM kehadiran
                    JOIN aktiviti ON kehadiran.idAktiviti = aktiviti.idAktiviti
                    JOIN ahli ON kehadiran.idAhli = ahli.idAhli
                    WHERE kehadiran.idAhli = '$idAhli'";

            $result = mysqli_query($conn, $sql);
            $bil_rekod = mysqli_num_rows($result);

            if ($bil_rekod > 0) {
                $kehadiran = mysqli_fetch_array($result);
                $Nama = $kehadiran["Nama"];

                echo "<div class='laporan'>
                        <h3 class='tajuk'>Senarai Nama Kehadiran Mengikut Ahli</h3>
                        <div class='details'>
                            <div>Nama: $Nama</div>
                        </div>
                      </div>";

                echo "<table class='senarai'>
                        <tr>
                            <th>idAhli</th>
                            <th>Aktiviti</th>
                            <th>Status</th>
                        </tr>";

                do {
                    echo "<tr>
                            <td>{$kehadiran['idAktiviti']}</td>
                            <td class='nama'>{$kehadiran['Tajuk']}</td>
                            <td>";
                    if ($kehadiran['Status'] == "Hadir") {
                        echo "<span class='status-yes'>&#10004;</span>";
                    } else if ($kehadiran['Status'] == "Tidak Hadir") {
                        echo "<span class='status-no'>&#10008;</span>";
                    }
                    echo "</td></tr>";
                } while ($kehadiran = mysqli_fetch_array($result));

                echo "</table>";
            } else {
                echo "<h3 style='text-align:center;margin-top:100px;color:red;'>Tiada Senarai Laporan Bagi Ahli</h3>";
            }
        }
    }
    ?>

    <button class="cetak" onclick="window.print()">Cetak</button>
</main>
</body>
</html>
