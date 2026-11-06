<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idAhli = $_POST["idAhli"];
    $Nama = $_POST["Nama"];
    $PasswordAhli = $_POST["PasswordAhli"];

    $sql_0 = "INSERT INTO ahli (idAhli, Nama, PasswordAhli) 
    VALUES ('$idAhli', '$Nama','$PasswordAhli')";

    try {
        if (mysqli_query($conn, $sql_0)) {
            mysqli_close($conn);
            echo "<script>
                    alert('Anda berjaya daftar');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            throw new Exception("Error: " . mysqli_error($conn));
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
    <title>Sign up</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="page-auth">
    <div id="container">
        <h1>Sign up</h1>
        <form action="daftar.php" method="post">

            <label for="Nama">Nama:</label>
            <input type="text" id="Nama" name="Nama" required>

            <label for="idAhli">idAhli:</label>
            <input type="text" id="idAhli" name="idAhli" placeholder="psmksk123" required>

            <div class="password-container">
                <label for="PasswordAhli">Password:</label>
                <input type="password" id="PasswordAhli" name="PasswordAhli" placeholder="Maximum 10 Perkataan" required maxlength="10">
                <span class="password-toggle" onclick="togglePasswordVisibility()">👁️</span>
            </div>

            <input type="submit" name="submit" value="Daftar">
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('PasswordAhli');
            const passwordToggle = document.querySelector('.password-toggle');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            passwordToggle.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        }

        const loginBtn = document.getElementById('login-btn');

        loginBtn.addEventListener('mouseover', () => {
            loginBtn.style.transform = 'scale(1.05)';
        });

        loginBtn.addEventListener('mouseout', () => {
            loginBtn.style.transform = 'scale(1)';
        });
    </script>
</body>
</html>
