<?php
session_start();
include("connect.php");

$idAktiviti = $_SESSION["idAktiviti"];

if (isset($_POST["submit"])) {
    $berjaya = true;

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    mysqli_begin_transaction($conn);

    try {
        if (isset($_POST['kehadiran']) && is_array($_POST['kehadiran'])) {
            foreach ($_POST['kehadiran'] as $idAhli => $value) {
                $sqlCheckStatus = "SELECT Status FROM kehadiran WHERE idAhli = '$idAhli' AND idAktiviti = '$idAktiviti'";
                $resultCheckStatus = mysqli_query($conn, $sqlCheckStatus);

                if (!$resultCheckStatus) {
                    throw new Exception('Error in check status query: ' . mysqli_error($conn));
                }

                $rowCheckStatus = mysqli_fetch_assoc($resultCheckStatus);

                if ($rowCheckStatus && isset($rowCheckStatus['Status']) && $rowCheckStatus['Status'] != 'Hadir') {
                    $status = 'Hadir';
                    $MasaHadir = 'NOW()';
                    $sqlUpdate = "REPLACE INTO kehadiran (idAhli, idAktiviti, Status, MasaHadir) 
                                  VALUES ('$idAhli', '$idAktiviti', '$status', $MasaHadir)";
                    if (!mysqli_query($conn, $sqlUpdate)) {
                        throw new Exception('Error in update query: ' . mysqli_error($conn));
                    }
                }
            }
        }

        $sqlCheckAll = "SELECT idAhli FROM ahli";
        $resultAll = mysqli_query($conn, $sqlCheckAll);

        if (!$resultAll) {
            throw new Exception('Error in check all query: ' . mysqli_error($conn));
        }

        while ($row = mysqli_fetch_assoc($resultAll)) {
            $idAhli = $row['idAhli'];

            if (!isset($_POST['kehadiran'][$idAhli])) {
                $sqlCheckStatus = "SELECT Status FROM kehadiran WHERE idAhli = '$idAhli' AND idAktiviti = '$idAktiviti'";
                $resultCheckStatus = mysqli_query($conn, $sqlCheckStatus);

                if (!$resultCheckStatus) {
                    throw new Exception('Error in check status query: ' . mysqli_error($conn));
                }

                $rowCheckStatus = mysqli_fetch_assoc($resultCheckStatus);

                if ($rowCheckStatus && isset($rowCheckStatus['Status']) && $rowCheckStatus['Status'] == 'Hadir') {
                    $sqlUpdate = "UPDATE kehadiran 
                                  SET Status = 'Tidak Hadir', MasaHadir = NULL 
                                  WHERE idAhli = '$idAhli' AND idAktiviti = '$idAktiviti'";
                    if (!mysqli_query($conn, $sqlUpdate)) {
                        throw new Exception('Error in update query: ' . mysqli_error($conn));
                    }
                }
            }
        }

        mysqli_commit($conn);
        echo "<script>
                alert('Kehadiran berjaya disahkan!');
                window.location.href = 'main_guru.php';
              </script>";

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>
                alert('Terdapat ralat semasa mengesahkan kehadiran: " . $e->getMessage() . "');
                window.location.href = 'main_guru.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahkan Kehadiran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_guru.php"); ?>
    <div class="container">
        <form action="sahkan_guru.php" method="POST">
            <div class="laporan">
                <h3 class="tajuk">Mengesahkan Kehadiran</h3>
                <span><label>Tajuk:</label> <?php echo $_SESSION['Tajuk']; ?></span>
                <span><label>Lokasi:</label> <?php echo $_SESSION['Lokasi']; ?></span>
                <span><label>Tarikh:</label> <?php echo $_SESSION['Tarikh']; ?></span>
                <span><label>Masa:</label> <?php echo $_SESSION['Masa']; ?></span>
            </div>

            <table>
                <tr>
                    <th>idAhli</th>
                    <th>Nama</th>
                    <th>Status</th>
                </tr>
                <?php
                    $sql = "SELECT kehadiran.idAhli, ahli.Nama, kehadiran.Status 
                            FROM kehadiran 
                            JOIN ahli ON kehadiran.idAhli = ahli.idAhli 
                            WHERE kehadiran.idAktiviti = '$idAktiviti'";

                    $result = mysqli_query($conn, $sql);
                    $bil_rekod = mysqli_num_rows($result);

                    if ($bil_rekod > 0) {
                        while ($kehadiran = mysqli_fetch_array($result)) {
                            $checked = ($kehadiran['Status'] == 'Hadir') ? "checked" : "";
                            echo "<tr>
                                    <td>{$kehadiran['idAhli']}</td>
                                    <td class='nama'>{$kehadiran['Nama']}</td>
                                    <td>
                                        <input type='checkbox' name='kehadiran[{$kehadiran['idAhli']}]' $checked>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Tiada Kehadiran Ahli</td></tr>";
                    }
                ?>
            </table>
            

<!-- Centered Submit Button -->
<button type="submit" name="submit" class="icon-button">
    <i class="fas fa-check-circle"></i> Sahkan Kehadiran
</button>

<!-- Centered Kembali Button -->
<div class="button kembali">
    <a href="main_guru.php">
        <button type="button" class="icon-button">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
    </a>
</div>

           
        </form>
    </div>
</body>
</html>
