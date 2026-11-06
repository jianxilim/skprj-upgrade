<?php
include("connect.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kehadiran Laporan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_ahli.php"); ?>

<main>
    <?php
       $sql = "SELECT kehadiran.*, aktiviti.Tajuk, ahli.Nama 
               FROM kehadiran 
               JOIN aktiviti ON kehadiran.idAktiviti = aktiviti.idAktiviti 
               JOIN ahli ON kehadiran.idAhli = ahli.idAhli 
               WHERE kehadiran.idAhli = '".$_SESSION["idAhli"]."'";

        $result = mysqli_query($conn, $sql);
        $bil_rekod = mysqli_num_rows($result);

        if ($bil_rekod > 0) {
            $kehadiran = mysqli_fetch_array($result);
            $Nama = htmlspecialchars($kehadiran["Nama"]);

            echo "<div class='laporan'>
                    <h3 class='Tajuk'>Kehadiran Anda</h3>
                    <h3 class='laporan'>Nama: $Nama<br></h3>
                  </div>";
            echo "<div class='table-container'>
                    <table class='senarai'>
                        <tr>
                            <th>ID Aktiviti</th>
                            <th>Tajuk</th>
                            <th>Kehadiran</th>
                        </tr>";

            do {
                $idAktiviti = htmlspecialchars($kehadiran['idAktiviti']);
                $Tajuk = htmlspecialchars($kehadiran['Tajuk']);
                $Status = ($kehadiran['Status'] == "Hadir") ? "Hadir" : "Tidak Hadir";

                echo "<tr>
                        <td>$idAktiviti</td>
                        <td class='Nama'>$Tajuk</td>
                        <td>$Status</td>
                      </tr>";
            } while ($kehadiran = mysqli_fetch_array($result));

            echo "    </table>
                  </div>";
        } else {
            echo "<h3 style='text-align:center; margin-top: 100px;color:red;'>TIADA SENARAI LAPORAN BAGI AHLI</h3>";
        }
    ?>

    <center>
        <button class="cetak" onclick='window.print()'>Cetak</button>
    </center>
</main>

</body>
</html>
