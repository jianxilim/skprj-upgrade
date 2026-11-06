<?php
session_start();
include("connect.php");

$idAktiviti = $_SESSION['idAktiviti'];
$berjaya = false;

if (isset($_POST["submit"])) {
    $sql = "SELECT idAhli FROM ahli";
    $result_ahli = mysqli_query($conn, $sql);

    while ($ahli = mysqli_fetch_array($result_ahli)) {
        if (isset($_POST["ahli"]) && in_array($ahli["idAhli"], $_POST["ahli"])) {
            $idAhli = $ahli["idAhli"];
            $sql = "INSERT INTO kehadiran (idAktiviti, idAhli, MasaHadir, Status) VALUES ('$idAktiviti', '$idAhli', NOW(), 'Hadir')";
            $result_kehadiran = mysqli_query($conn, $sql);
            
            if ($result_kehadiran) {
                $berjaya = true;
            } else {
                $berjaya = false;
            }
        }
    }

    if ($berjaya) {
        echo "<script>
                alert('Kehadiran berjaya ditambah!');
                window.location.href = 'tambah_guru.php';
              </script>";
    } else {
        echo "<script>
                alert('Terdapat ralat semasa menambah kehadiran.');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kehadiran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_guru.php"); ?>
    <div class="form-container">
        <form action="tambah_guru.php" method="post">
            <div class='laporan'>
                <h3 class='tajuk'>Menambahkan Kehadiran Ahli</h3>
                <div class='laporan'>
                    <span><label>Tajuk:</label> <?php echo $_SESSION['Tajuk']; ?></span>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <tr>
                        <th>idAhli</th>
                        <th>Nama</th>
                        <th>Tindakan</th>
                    </tr>

                    <?php
                        $sql = "SELECT ahli.idAhli, ahli.Nama 
                                FROM ahli 
                                WHERE ahli.idAhli NOT IN (SELECT idAhli FROM kehadiran WHERE idAktiviti='$idAktiviti')";
                        
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($ahli = mysqli_fetch_array($result)) {
                                echo "<tr>
                                        <td>{$ahli['idAhli']}</td>
                                        <td class='nama'>{$ahli['Nama']}</td>
                                        <td><input type='checkbox' name='ahli[]' value='{$ahli['idAhli']}'></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>Semua Ahli Telah Ditambah</td></tr>";
                        }
                    ?>
                </table>
            </div>

            <button type="submit" name="submit">Tambah Kehadiran</button>
            <div class="button kembali"><a href="main_guru.php"><button type="button">Kembali</button></a></div>
        </form>
    </div>
</body>
</html>
