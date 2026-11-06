<?php
include("connect.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idGuru = $_POST["idGuru"];
    $NamaGuru = $_POST["NamaGuru"];
    $PasswordGuru = $_POST["PasswordGuru"];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO guru (idGuru, NamaGuru, PasswordGuru) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $idGuru, $NamaGuru, $PasswordGuru);

    try {
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            echo "<script>
                    alert('Anda berjaya daftar');
                    window.location.href = 'tambahguru_guru.php';
                  </script>";
            exit();
        } else {
            throw new Exception("Error: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_guru.php"); ?>
    <div id="container">
        <h1>Tambah Guru</h1>
        <form action="tambahguru_guru.php" method="post">
            <label for="NamaGuru">Nama:</label>
            <input type="text" id="NamaGuru" name="NamaGuru" required>

            <label for="idGuru">idGuru:</label>
            <input type="text" id="idGuru" name="idGuru" placeholder="admin0" required>

            <div class="password-container">
                <label for="PasswordGuru">Password:</label>
                <input type="password" id="PasswordGuru" name="PasswordGuru" placeholder="Maximum 10 Perkataan" required maxlength="10">
                <span class="password-toggle" onclick="togglePasswordVisibility()">👁️</span>
            </div>

            <input type="submit" name="submit" value="Daftar">
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('PasswordGuru');
            const passwordToggle = document.querySelector('.password-toggle');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            passwordToggle.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        }
    </script>
</body>
</html>
