<?php
session_start();
include("connect.php");


function fetchGurus($conn, $idGuru = null) {
    $Gurus = array();

    try {
        if ($idGuru === null) {
            $sql = "SELECT * FROM guru";
        } else {
            $sql = "SELECT * FROM guru WHERE idGuru = ?";
        }
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            if ($idGuru !== null) {
                mysqli_stmt_bind_param($stmt, "s", $idGuru);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $Gurus[] = $row;
                }
            }
            mysqli_stmt_close($stmt);
        }

        return $Gurus;
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle delete action
if (isset($_POST["delete"])){
    $idGuru = $_POST["idGuru"];

    // Perform deletion queries
    $sqlDeleteGuru = "DELETE FROM guru WHERE idGuru = ?";
    
    // Execute deletion queries
    if ($stmt = mysqli_prepare($conn, $sqlDeleteGuru)) {
        mysqli_stmt_bind_param($stmt, "s", $idGuru);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to refresh the page
            header("Location: piliheditguru_guru.php");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}

if (isset($_GET["cari"])) {
    $idGuru = $_GET["idGuru"];
    $Gurus = fetchGurus($conn, $idGuru);

} elseif (isset($_GET["reset"])) {
    header("Location: piliheditguru_guru.php");
    exit();

} else {
    $Gurus = fetchGurus($conn);
}

if (isset($_POST["kemaskini_guru"])) {
    $idGuru = mysqli_real_escape_string($conn, $_POST["kemaskini_idGuru"]);
    $_SESSION["idGuru"] = $idGuru;

    $sql_1 = "SELECT * FROM guru WHERE idGuru = ?";

    if ($stmt_1 = mysqli_prepare($conn, $sql_1)) {
        mysqli_stmt_bind_param($stmt_1, "s", $idGuru);
        mysqli_stmt_execute($stmt_1);
        $result_1 = mysqli_stmt_get_result($stmt_1);

        if ($row_1 = mysqli_fetch_assoc($result_1)) {
            $_SESSION["NamaGuru"] = $row_1["NamaGuru"];
            $_SESSION["PasswordGuru"] = $row_1["PasswordGuru"];
        } else {
            echo "No data found for ID Guru: " . htmlspecialchars($idGuru);
        }

        mysqli_stmt_close($stmt_1);
    }

    header("Location: editguru_guru.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Guru Kelab E-sport</title>



    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this admin?");
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
    <h1>Urus Guru Kelab E-sport</h1>

    <form action="piliheditguru_guru.php" method="GET">
        <label for="idGuru">idGuru:</label>
        <input type="text" id="idGuru" name="idGuru" autocomplete="off" placeholder="admin0">
        <button type="submit" name="cari" class="icon-button"><i class="fas fa-search"></i></button>
        <button type="submit" name="reset" class="icon-button"><i class="fas fa-sync-alt"></i></button>
    </form>
    
    <div class="table-container">
        <table>
            <tr>
                <th>idGuru</th>
                <th>NamaGuru</th>
                <th>PasswordGuru</th>
                <th colspan="2">Tindakan</th>
            </tr>

            <?php if (!empty($Gurus)): ?>
                <?php foreach ($Gurus as $Guru): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($Guru["idGuru"]); ?></td>
                        <td><?php echo htmlspecialchars($Guru["NamaGuru"]); ?></td>
                        <td><?php echo htmlspecialchars($Guru["PasswordGuru"]); ?></td>
                   
                        <td>
                            <form action="piliheditguru_guru.php" method="post">
                                <input type="hidden" name="kemaskini_idGuru" value="<?php echo htmlspecialchars($Guru['idGuru']); ?>">
                                <button type="submit" name="kemaskini_guru" class="icon-button" title="Kemaskini">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="piliheditguru_guru.php" method="POST" onsubmit="return confirmDelete();">
                                <input type="hidden" name="idGuru" value="<?php echo htmlspecialchars($Guru['idGuru']); ?>">
                                <button type="submit" name="delete" class="icon-button" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Tiada Guru</td>
                </tr>
            <?php endif; ?>
        </table>
  
<div class="center">
    <button id="printButton" class="icon-button" onclick="printPage()">
        <i class="fas fa-print"></i> Cetak
    </button>
    <a href="main_guru.php" class="icon-button back-button">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>


</body>
</html>
