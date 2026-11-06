<?php
session_start();
include("connect.php");


function fetchAhlis($conn, $idAhli = null) {
    $ahlis = array();

    try {
        if ($idAhli === null) {
            $sql = "SELECT * FROM ahli";
        } else {
            $sql = "SELECT * FROM ahli WHERE idAhli = ?";
        }
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            if ($idAhli !== null) {
                mysqli_stmt_bind_param($stmt, "s", $idAhli);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $ahlis[] = $row;
                }
            }
            mysqli_stmt_close($stmt);
        }

        return $ahlis;
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle delete action
if (isset($_POST["delete"])){
    $idAhli = $_POST["idAhli"];

    // Perform deletion queries
    $sqlDeleteAhli = "DELETE FROM ahli WHERE idAhli = ?";
    
    // Execute deletion queries
    if ($stmt = mysqli_prepare($conn, $sqlDeleteAhli)) {
        mysqli_stmt_bind_param($stmt, "s", $idAhli);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to refresh the page
            header("Location: piliheditahli_guru.php");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}

if (isset($_GET["cari"])) {
    $idAhli = $_GET["idAhli"];
    $ahlis = fetchAhlis($conn, $idAhli);

} elseif (isset($_GET["reset"])) {
    header("Location: piliheditahli_guru.php");
    exit();

} else {
    $ahlis = fetchAhlis($conn);
}

if (isset($_POST["kemaskini_ahli"])) {
    $idAhli = mysqli_real_escape_string($conn, $_POST["kemaskini_idAhli"]);
    $_SESSION["idAhli"] = $idAhli;

    $sql_1 = "SELECT * FROM ahli WHERE idAhli = ?";

    if ($stmt_1 = mysqli_prepare($conn, $sql_1)) {
        mysqli_stmt_bind_param($stmt_1, "s", $idAhli);
        mysqli_stmt_execute($stmt_1);
        $result_1 = mysqli_stmt_get_result($stmt_1);

        if ($row_1 = mysqli_fetch_assoc($result_1)) {
            $_SESSION["Nama"] = $row_1["Nama"];
            $_SESSION["PasswordAhli"] = $row_1["PasswordAhli"];
        } else {
            echo "No data found for ID Ahli: " . htmlspecialchars($idAhli);
        }

        mysqli_stmt_close($stmt_1);
    }

    header("Location: editahli_guru.php");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Ahli Kelab E-sport</title>



    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this member?");
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
    <h1>Urus Ahli Kelab E-sport</h1>

    <form action="piliheditahli_guru.php" method="GET">
        <label for="idAhli">idAhli:</label>
        <input type="text" id="idAhli" name="idAhli" autocomplete="off" placeholder="psmksk123">
        <button type="submit" name="cari" class="icon-button"><i class="fas fa-search"></i></button>
        <button type="submit" name="reset" class="icon-button"><i class="fas fa-sync-alt"></i></button>
    </form>
    
    <div class="table-container">
        <table>
            <tr>
                <th>idAhli</th>
                <th>Nama</th>
                <th>PasswordAhli</th>
                <th colspan="2">Tindakan</th>
            </tr>

            <?php if (!empty($ahlis)): ?>
                <?php foreach ($ahlis as $ahli): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ahli["idAhli"]); ?></td>
                        <td><?php echo htmlspecialchars($ahli["Nama"]); ?></td>
                        <td><?php echo htmlspecialchars($ahli["PasswordAhli"]); ?></td>
                   
                        <td>
                            <form action="piliheditahli_guru.php" method="post">
                                <input type="hidden" name="kemaskini_idAhli" value="<?php echo htmlspecialchars($ahli['idAhli']); ?>">
                                <button type="submit" name="kemaskini_ahli" class="icon-button" title="Kemaskini">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="piliheditahli_guru.php" method="POST" onsubmit="return confirmDelete();">
                                <input type="hidden" name="idAhli" value="<?php echo htmlspecialchars($ahli['idAhli']); ?>">
                                <button type="submit" name="delete" class="icon-button" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Tiada Ahli</td>
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
