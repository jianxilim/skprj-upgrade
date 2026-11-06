<?php
include("connect.php");

session_start();

$error_message = "";

if (isset($_POST["submit"])) {
    $idGuru = $_POST["idGuru"];
    $PasswordGuru = $_POST["PasswordGuru"];

    try {
        $sql = "SELECT * FROM guru WHERE idGuru = '$idGuru' AND PasswordGuru='$PasswordGuru'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $_SESSION["idGuru"] = $row["idGuru"];
            $_SESSION["NamaGuru"] = $row["NamaGuru"];
            $_SESSION["PasswordGuru"] = $row["PasswordGuru"];

            // Display success message and redirect to the main guru page
            echo "<script>
                    alert('Anda berjaya log in');
                    window.location.href = 'main_guru.php';
                  </script>";
            exit();
        } else {
            $error_message = "idGuru atau Password yang salah";
        }

        mysqli_close($conn);

    } catch (mysqli_sql_exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem rekod kehadiran ahli kelab E-Sport SMK Seri Kembangan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="page-auth">
<div id="container">
    <header>
        <h3>Sign in</h3>
    </header>
    <div class="login">
        <form action="login_guru.php" method="post">
            <div>
                <label for="idGuru">idGuru:</label>
                <input type="text" id="idGuru" name="idGuru">
            </div>
            <br>
            <div class="password-container">
                <label for="PasswordGuru">Password:</label>
                <input type="password" id="PasswordGuru" name="PasswordGuru" required>
                <span class="password-toggle" onclick="togglePasswordVisibility()">👁️</span>
            </div>
            <br>
            <div>
                <input type="submit" name="submit" value="Log in" id="login-btn">
            </div>
            <?php if ($error_message): ?>
            <div style="color: red; margin-top: 10px;"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <div class="footer">
                <div><a href="login_ahli.php">Saya ialah Ahli</a></div>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('PasswordGuru');
        const passwordToggle = document.querySelector('.password-toggle');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordToggle.textContent = '👁️';
        } else {
            passwordInput.type = 'password';
            passwordToggle.textContent = '🔒';
        }
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
