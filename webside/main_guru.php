<?php
session_start();
include("connect.php");

function fetchAktivitis($conn, $idAktiviti = null) {
    $aktivitis = array();

    try {
        if ($idAktiviti === null) {
            $sql = "SELECT * FROM aktiviti";
        } else {
            $sql = "SELECT * FROM aktiviti WHERE idAktiviti='$idAktiviti'";
        }

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $aktivitis[] = $row;
            }
        }
        return $aktivitis;
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_GET["cari"])) {
    $idAktiviti = $_GET["idAktiviti"];
    $aktivitis = fetchAktivitis($conn, $idAktiviti);
} elseif (isset($_GET["reset"])) {
    header("Location: main_guru.php");
    exit();
} else {
    $aktivitis = fetchAktivitis($conn);
}

if(isset($_POST['tambah_kehadiran'])) {
    $_SESSION["idAktiviti"] = $_POST["tambah_idAktiviti"];
    $sql_1 = "SELECT * FROM aktiviti WHERE idAktiviti='{$_SESSION["idAktiviti"]}'";
    $result_1 = mysqli_query($conn, $sql_1);
    $row_1 = mysqli_fetch_assoc($result_1);

    $_SESSION["Tajuk"] = $row_1["Tajuk"];

    header("Location: tambah_guru.php");
    exit(); 
}

if(isset($_POST['sahkan_kehadiran'])) {
    $_SESSION["idAktiviti"] = $_POST["sahkan_idAktiviti"];
    $sql_1 = "SELECT * FROM aktiviti WHERE idAktiviti='{$_SESSION["idAktiviti"]}'";
    $result_1 = mysqli_query($conn, $sql_1);
    $row_1 = mysqli_fetch_assoc($result_1);

    $_SESSION["Tajuk"] = $row_1["Tajuk"];
    $_SESSION["Tarikh"] = $row_1["Tarikh"];
    $_SESSION["Masa"] = $row_1["Masa"];
    $_SESSION["Lokasi"] = $row_1["Lokasi"];
    $_SESSION["Gambar"] = $row_1["Gambar"];

    header("Location: sahkan_guru.php");
    exit(); 
}

if(isset($_POST['kemaskini_aktiviti'])) {
    $_SESSION["idAktiviti"] = $_POST["kemaskini_idAktiviti"];
    $sql_1 = "SELECT * FROM aktiviti WHERE idAktiviti='{$_SESSION["idAktiviti"]}'";
    $result_1 = mysqli_query($conn, $sql_1);
    $row_1 = mysqli_fetch_assoc($result_1);

    $_SESSION["Tajuk"] = $row_1["Tajuk"];
    $_SESSION["Tarikh"] = $row_1["Tarikh"];
    $_SESSION["Masa"] = $row_1["Masa"];
    $_SESSION["Lokasi"] = $row_1["Lokasi"];
    $_SESSION["Gambar"] = $row_1["Gambar"];

    header("Location: kemaskini_aktiviti.php");
    exit(); 
}

if (isset($_POST['delete_aktiviti'])) {
    $delete_idAktiviti = $_POST['delete_idAktiviti'];
    $sql_delete = "DELETE FROM aktiviti WHERE idAktiviti='$delete_idAktiviti'";
    mysqli_query($conn, $sql_delete);
    header("Location: main_guru.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahkan Aktiviti Kelab E-sport</title>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this activity?");
        }

        function printPage() {
            window.print();
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_guru.php"); ?>
    <div id="container">
        <h1>Senarai Aktiviti Kelab E-sport</h1>
        <form action="main_guru.php" method="GET">
            <label for="idAktiviti">idAktiviti:</label>
            <input type="text" id="idAktiviti" name="idAktiviti" autocomplete="off" placeholder="A100">
            <button type="submit" name="cari" class="icon-button" title="Cari"><i class="fas fa-search"></i></button>
            <button type="submit" name="reset" class="icon-button" title="Reset"><i class="fas fa-sync"></i></button>
        </form>

        <div class="table-container">
            <table>
                <tr>
                    <th>idAktiviti</th>
                    <th>Tajuk</th>
                    <th>Tarikh</th>
                    <th>Masa</th>
                    <th>Lokasi</th>
                    <th>Gambar</th>
                    <th>Kehadiran</th>
                    <th>Tindakan</th>
                </tr>

                <?php if (!empty($aktivitis)): ?>
                    <?php foreach ($aktivitis as $aktiviti): ?>
                        <tr>
                            <td><?php echo $aktiviti["idAktiviti"]; ?></td>
                            <td><?php echo $aktiviti["Tajuk"]; ?></td>
                            <td><?php echo $aktiviti["Tarikh"]; ?></td>
                            <td><?php echo $aktiviti["Masa"]; ?></td>
                            <td><?php echo $aktiviti["Lokasi"]; ?></td>
                            <td><img width=100 src='<?php echo $aktiviti["Gambar"]; ?>'></td>
                            <td>
                                <form action="main_guru.php" method="post" style="display:inline;">
                                    <input type="hidden" name="tambah_idAktiviti" value="<?php echo $aktiviti['idAktiviti']; ?>">
                                    <button type="submit" name="tambah_kehadiran" class="icon-button" title="Tambah"><i class="fas fa-plus-circle"></i></button>
                                </form>
                                <form action="main_guru.php" method="post" style="display:inline;">
                                    <input type="hidden" name="sahkan_idAktiviti" value="<?php echo $aktiviti['idAktiviti']; ?>">
                                    <button type="submit" name="sahkan_kehadiran" class="icon-button" title="Sahkan"><i class="fas fa-check-circle"></i></button>
                                </form>
                            </td>
                            <td>
                                <form action="main_guru.php" method="post" style="display:inline;">
                                    <input type="hidden" name="kemaskini_idAktiviti" value="<?php echo $aktiviti['idAktiviti']; ?>">
                                    <button type="submit" name="kemaskini_aktiviti" class="icon-button" title="Kemaskini"><i class="fas fa-sync-alt"></i></button>
                                </form>
                                <form action="main_guru.php" method="post" style="display:inline;" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="delete_idAktiviti" value="<?php echo $aktiviti['idAktiviti']; ?>">
                                    <button type="submit" name="delete_aktiviti" class="icon-button" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Tiada Aktiviti</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>
