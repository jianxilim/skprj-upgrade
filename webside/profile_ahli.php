<?php
session_start();

include("connect.php");

// Check if the form is submitted
if (isset($_POST["Kemaskini"])) {
    // Sanitize user inputs
    $newNama = mysqli_real_escape_string($conn, $_POST["Nama"]);
    $newPasswordAhli = mysqli_real_escape_string($conn, $_POST["PasswordAhli"]);

    // Update the session variables
    $_SESSION["Nama"] = $newNama;
    $_SESSION["PasswordAhli"] = $newPasswordAhli;

    // Update the database
    $sql2 = "UPDATE ahli SET Nama='$newNama', PasswordAhli='$newPasswordAhli' WHERE idAhli='{$_SESSION['idAhli']}'";

    // Execute query and check for errors
    if (mysqli_query($conn, $sql2)) {
        // Redirect to profile page after updating
        header("Location: profile_ahli.php");
        exit();
    } else {
        $error_message = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_ahli.php"); ?>
    <div id="container">
        <h1>Profile Saya</h1>
        <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>
        <form action="profile_ahli.php" method="POST" id="updateForm">
            <table>
                <tr>
                    <th id="idAhli-label">idAhli:</th>
                    <td><?php echo $_SESSION["idAhli"]; ?></td>
                </tr>
                <tr>
                    <th id="Nama-label">Nama:</th>
                    <td><input type="text" name="Nama" value="<?php echo htmlspecialchars($_SESSION["Nama"]); ?>" required autocomplete="off"></td>
                </tr>
                <tr>
                    <th id="PasswordAhli-label">PasswordAhli:</th>
                    <td class="password-input">
                        <input type="password" id="PasswordAhli" name="PasswordAhli" value="<?php echo htmlspecialchars($_SESSION["PasswordAhli"]); ?>" required autocomplete="off">
                        <i class="fas fa-eye-slash toggle-password" onclick="togglePasswordVisibility()"></i>
                    </td>
                </tr>
            </table>
            <div class="button-container">
                <button type="submit" name="Kemaskini" title="Kemaskini" class="kemaskini" onclick="return confirm('Are you sure you want to update?')">
                    <i class="fas fa-pencil-alt"></i> Update
                </button>
                <button type="reset" id="reset" title="Reset" class="reset" onclick="resetForm()">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </form>
        <div class="color-buttons">
            <button class="red" onclick="changeColor('red')">Red</button>
            <button class="green" onclick="changeColor('green')">Green</button>
            <button class="blue" onclick="changeColor('blue')">Blue</button>
            <button class="black" onclick="changeColor('black')">Black</button>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById('PasswordAhli');
            var icon = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        function resetForm() {
            document.getElementById('updateForm').reset();
        }

        function changeColor(color) {
            document.getElementById('idAhli-label').style.color = color;
            document.getElementById('Nama-label').style.color = color;
            document.getElementById('PasswordAhli-label').style.color = color;
        }
    </script>
</body>
</html>
