<?php
session_start();

include("connect.php");
date_default_timezone_set('Asia/Kuala_Lumpur');

// Check if the form is submitted
if (isset($_POST["Kemaskini"])) {
    // Sanitize user inputs
    $newTajuk = mysqli_real_escape_string($conn, $_POST["Tajuk"]);
    $newTarikh = mysqli_real_escape_string($conn, $_POST["Tarikh"]);
    $newMasa = mysqli_real_escape_string($conn, $_POST["Masa"]);
    $newLokasi = mysqli_real_escape_string($conn, $_POST["Lokasi"]);

    $current_date = date('Y-m-d');
    $current_time = date('H:i');
    
    if(($newTarikh > $current_date) || ($newTarikh == $current_date && $current_time > $newMasa)){
        // Update the session variables
        $_SESSION["Tajuk"] = $newTajuk;
        $_SESSION["Tarikh"] = $newTarikh;
        $_SESSION["Masa"] = $newMasa;
        $_SESSION["Lokasi"] = $newLokasi;

        // Handle file upload
        if ($_FILES["Gambar"]["error"] === 0) {
            $Gambar = $_SESSION["idAktiviti"].".jpg";
            $Sementara = $_FILES["Gambar"]["tmp_name"];

            if (move_uploaded_file($Sementara, "$Gambar")) {
                $_SESSION["Gambar"] = $Gambar;
            } else {
                echo "Error uploading file";
            }
        } else {
            // Keep the existing file name
            $Gambar = $_SESSION["Gambar"];
        }

        // Update the database
        $sql = "UPDATE aktiviti SET Tajuk='$newTajuk', Tarikh='$newTarikh', Masa='$newMasa',  Lokasi='$newLokasi', Gambar='{$_SESSION['Gambar']}' WHERE idAktiviti='{$_SESSION['idAktiviti']}'";
        mysqli_query($conn, $sql);

        // Check for errors and provide feedback
        if (mysqli_errno($conn) !== 0) {
            $error_message = "Error updating activity: " . mysqli_error($conn);
            echo "<script>
                alert('Kemaskini gagal: " . addslashes($error_message) . "');
                window.location.href = 'kemaskini_aktiviti.php';
            </script>";
        } else {
            echo "<script>
                alert('Aktiviti berjaya dikemaskini!');
                window.location.href = 'kemaskini_aktiviti.php';
            </script>";
        }

    } else{
        echo "<div class='error'>Kesalahan Tarikh/Masa</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini aktiviti</title>

    <!-- Font Awesome styles -->

    <!-- Other styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_guru.php"); ?>
    <div id="container">
        <h1>Kemaskini Aktiviti</h1>
        <form action="kemaskini_aktiviti.php" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <th>idAktiviti :</th>
                    <td><?php echo $_SESSION["idAktiviti"]; ?></td>
                </tr>
                <tr>
                    <th>Tajuk :</th>
                    <td><input type="text" name="Tajuk" value="<?php echo htmlspecialchars($_SESSION["Tajuk"]); ?>" required autocomplete="off"></td>
                </tr>
                <tr>
                    <th>Tarikh :</th>
                    <td><input type="date" name="Tarikh" value="<?php echo htmlspecialchars($_SESSION["Tarikh"]); ?>" required autocomplete="off"></td>
                </tr>
                <tr>
                    <th>Masa :</th>
                    <td><input type="time" name="Masa" value="<?php echo htmlspecialchars($_SESSION["Masa"]); ?>" required autocomplete="off"></td>
                </tr>
                <tr>
                    <th>Lokasi :</th>
                    <td><input type="text" name="Lokasi" value="<?php echo htmlspecialchars($_SESSION["Lokasi"]); ?>" required autocomplete="off"></td>
                </tr>
                <tr>
                    <th>Gambar 500x400:</th>
                    <td>
                        <input type="file" name="Gambar" accept=".jpg" autocomplete="off">
                        <?php echo "<img width=100 src='img/{$_SESSION["Gambar"]}'>" ?>
                    </td>
                </tr>
            </table>
            <br>
            <div class="button-container">
                <button type="submit" name="Kemaskini" onclick="return confirm('Are you sure you want to update?')"><i class="fas fa-edit"></i>Kemaskini</button>
                <button type="button" id="reset" onclick="window.location.href='kemaskini_aktiviti.php';"><i class="fas fa-redo"></i>Reset</button>
                <button type="button" id="kembali" onclick="window.location.href='main_guru.php'"><i class="fas fa-arrow-left"></i>Kembali</button>
            </div>
        </form>
    </div>
</body>
</html>
