<?php
session_start(); 

include("connect.php");

date_default_timezone_set('Asia/Kuala_Lumpur');

$sql = "SELECT idAktiviti, idGuru, Tajuk, Tarikh, Gambar, Lokasi, Masa FROM aktiviti"; // Added Gambar in SELECT statement
$result = $conn->query($sql);

$message = ''; // Initialize message variable

if (isset($_POST['Daftar'])) {
    $aktivitiId = $_POST['Daftar'];
    $aktivitiId = mysqli_real_escape_string($conn, $aktivitiId);

    $sql = "SELECT * FROM aktiviti WHERE idAktiviti='$aktivitiId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tarikh = $row["Tarikh"];
        $masa = $row["Masa"];
        $currentMasa = date('H:i:s');
        $currentDate = date('Y-m-d');

        if ($currentDate > $tarikh || ($currentDate == $tarikh && $currentMasa < $masa)) {
            $status = "Hadir";
        } else {
            $status = "Tidak Hadir";
        }

        $idAhli = $_SESSION['idAhli'];

        // Check if the user has already registered
        $sql_check = "SELECT * FROM kehadiran WHERE idAktiviti='$aktivitiId' AND idAhli='$idAhli'";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows > 0) {
            $message = "Anda telah daftar"; // User already registered
        } else {
            $sql_1 = "INSERT INTO kehadiran (idAktiviti, idAhli, MasaHadir, Status) VALUES ('$aktivitiId', '$idAhli', NOW(), '$status')";

            if(mysqli_query($conn, $sql_1)) {
                $message = "Anda berjaya daftar"; // Registration successful
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktiviti Kelab E-sport</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_ahli.php"); ?>
    <div class="container">
        <h1>Aktiviti Kelab E-sport</h1>
        <?php if ($message): ?>
            <script>
                alert('<?php echo $message; ?>');
                window.location.href = 'main_ahli.php'; // Redirect to main_ahli.php
            </script>
        <?php endif; ?>
        <table>
            <tr>
                <th>idAktiviti</th>
                <th>idGuru</th>
                <th>Tajuk</th>
                <th>Tarikh</th>
                <th>Gambar</th> <!-- Added Gambar header -->
                <th>Lokasi</th>
                <th>Masa</th>
                <th>Hadir</th>
              
            </tr>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["idAktiviti"] . "</td>";
                        echo "<td>" . $row["idGuru"] . "</td>";
                        echo "<td>" . $row["Tajuk"] . "</td>";
                        echo "<td>" . $row["Tarikh"] . "</td>";
                        echo "<td><img width=100 src='" . $row["Gambar"] . "'></td>"; // Added Gambar column
                        echo "<td>" . $row["Lokasi"] . "</td>";
                        echo "<td>" . $row["Masa"] . "</td>";
                        echo "<td><button name='Daftar' value='" . $row["idAktiviti"] . "'>Daftar</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>0 results</td></tr>"; // Updated colspan
                }
                ?>
            </form>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
