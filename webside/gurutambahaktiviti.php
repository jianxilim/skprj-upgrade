<?php
session_start();
include("connect.php");
date_default_timezone_set('Asia/Kuala_Lumpur');

// Initialize success flag and error message
$success = false;
$error_message = '';

// Get current date and time
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// Check latest activity ID
$sql = "SELECT * FROM aktiviti ORDER BY idAktiviti DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $LatestidAktiviti = $row["idAktiviti"];
    $numericPart = substr($LatestidAktiviti, 1);
    $numericPart = intval($numericPart) + 100;
    $LatestidAktiviti = "A" . strval($numericPart);
} else {
    $LatestidAktiviti = "A100";
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $Tajuk = $_POST["Tajuk"];
    $Tarikh = $_POST["Tarikh"];
    $Masa = $_POST["Masa"];
    $Lokasi = $_POST["Lokasi"];
    $Gambar = $LatestidAktiviti.".jpg";
    $Sementara = $_FILES["Gambar"]["tmp_name"];

    // Validate date and time
if (($Tarikh > $current_date) || ($Tarikh == $current_date && $Masa > $current_time)) {
        if (move_uploaded_file($Sementara, "$Gambar")) {
            // Insert data into database
            $sql_1 = "INSERT INTO aktiviti (idAktiviti, Tajuk, Tarikh, Masa, Lokasi, Gambar, idGuru) 
            VALUES ('$LatestidAktiviti', '$Tajuk', '$Tarikh', '$Masa', '$Lokasi', '$Gambar', '" . $_SESSION["idGuru"] . "')";

            try {
                mysqli_query($conn, $sql_1);
                $success = true;
                header("Location: ".$_SERVER['PHP_SELF']."?success=true");
                exit();
            } catch (mysqli_sql_exception $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        } else {
            $error_message = "Error uploading file";
        }
    } else {
        $error_message = "Kesalahan Tarikh/Masa";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Tambah Aktiviti Kelab E-sport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_guru.php"); ?>
    <div class="form">
        <h1>Borang Tambah Aktiviti Kelab E-sport</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" id="aktivitiForm" enctype="multipart/form-data">
            <p class="aktiviti-info">idAktiviti: <?php echo $LatestidAktiviti; ?></p>
            <div>
                <label for="Tajuk">Tajuk:</label>
                <input type="text" id="Tajuk" name="Tajuk" required autocomplete="off">
            </div>
            <div>
                <label for="Tarikh">Tarikh:</label>
                <input type="date" id="Tarikh" name="Tarikh" required autocomplete="off">
            </div>
            <div>
                <label for="Masa">Masa:</label>
                <input type="time" id="Masa" name="Masa" required autocomplete="off">
            </div>
            <div>
                <label for="Lokasi">Lokasi:</label>
                <input type="text" id="Lokasi" name="Lokasi" required autocomplete="off">
            </div>
            <div>
                <label for="Gambar">Gambar 500x400:</label>
                <input type="file" id="Gambar" name="Gambar" accept=".jpg" required autocomplete="off">
            </div>
            <div>
                <input type="submit" name="submit" value="Tambah" id="submitBtn">
            </div>
        </form>
        <?php
        // Display error message if there's any
        if (!empty($error_message)) {
            echo "<div class='error'>$error_message</div>";
        }
        ?>
    </div>
    <script>
        document.getElementById("submitBtn").addEventListener("click", function(event) {
            var confirmation = confirm("Are you sure you want to add this activity?");
            if (!confirmation) {
                event.preventDefault(); // Prevent form submission if user cancels
            }
        });

        // Check URL parameters for success flag
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === 'true') {
            alert('Aktiviti berjaya ditambah!');
        } else if (urlParams.get('success') === 'false') {
            alert('Gagal menambah aktiviti. Sila cuba lagi.');
        }
    </script>
</body>
</html>
