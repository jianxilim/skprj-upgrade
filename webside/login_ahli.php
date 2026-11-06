<?php
include("connect.php");
session_start();

$error_message = ""; // Initialize the error message variable

if (isset($_POST["submit"])) { // Check if the form is submitted
    $idAhli = $_POST["idAhli"]; // Retrieve the idAhli from POST data
    $PasswordAhli = $_POST["PasswordAhli"]; // Retrieve the PasswordAhli from POST data
    
    try {
        $sql = "SELECT * FROM ahli WHERE idAhli = '$idAhli' AND PasswordAhli='$PasswordAhli'"; // SQL query to check credentials
        $result = mysqli_query($conn, $sql); // Execute the query
        
        if (mysqli_num_rows($result) > 0) { // Check if any rows are returned
            $row = mysqli_fetch_assoc($result); // Fetch the resulting row
            
            $_SESSION["idAhli"] = $row["idAhli"]; // Set session variables
            $_SESSION["Nama"] = $row["Nama"];
            $_SESSION["PasswordAhli"] = $row["PasswordAhli"];
            $_SESSION["success_login"] = true;
            
            // Display success message and redirect to the main member page
            echo "<script>
                    alert('Anda berjaya log in'); // Alert success message
                    window.location.href = 'main_ahli.php'; // Redirect to main_ahli.php
                  </script>";
            exit();
        } else {
            $error_message = "idAhli atau Password yang salah"; // Set the error message if credentials are incorrect
        }
        
        mysqli_close($conn); // Close the database connection
    } catch (mysqli_sql_exception $e) { // Catch any SQL exceptions
        echo "Error: " . $e->getMessage(); // Display the error message
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Specify the character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive meta tag -->
    <title>Sistem rekod kehadiran ahli kelab E-Sport SMK Seri Kembangan</title> <!-- Page title -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="page-auth">
<div id="container"> <!-- Main container div -->
    <h1>Sign in</h1> <!-- Heading for the sign-in form -->
    <div class="login"> <!-- Login form container -->
        <form action="login_ahli.php" method="POST"> <!-- Form submission details -->
            <div>
                <label for="idAhli">idAhli:</label> <!-- Label for idAhli input -->
                <input type="text" id="idAhli" name="idAhli" placeholder="psmksk" required> <!-- idAhli input field -->
            </div>
            <br>
            <div class="password-container"> <!-- Container for the password input and toggle -->
                <label for="PasswordAhli">Password:</label> <!-- Label for PasswordAhli input -->
                <input type="password" id="PasswordAhli" name="PasswordAhli" required> <!-- PasswordAhli input field -->
                <span class="password-toggle" onclick="togglePasswordVisibility()">👁️</span> <!-- Password visibility toggle icon -->
            </div>
            <br>
            <div>
                <input type="submit" name="submit" value="Log in" id="login-btn"> <!-- Submit button -->
            </div>
            <?php if ($error_message): ?> <!-- Check if there's an error message -->
                <div style="color: red; margin-top: 10px;"><?php echo $error_message; ?></div> <!-- Display error message -->
            <?php endif; ?>
        </form>
    </div>
    <div class="footer"> <!-- Footer container -->
        <div><a href="login_guru.php">Saya ialah Guru</a></div> <!-- Link for teacher login -->
        <div>Belum daftar? <br><a href="daftar.php">Daftar di sini<br></a></div> <!-- Link for registration -->
    </div>
</div>

<script>
    function togglePasswordVisibility() { // Function to toggle password visibility
        const passwordField = document.getElementById('PasswordAhli'); // Get the password field element
        const passwordToggle = document.querySelector('.password-toggle'); // Get the toggle icon element
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password'; // Determine the new type
        passwordField.setAttribute('type', type); // Set the new type for the password field
        passwordToggle.textContent = type === 'password' ? '👁️' : '👁️‍🗨️'; // Update the toggle icon
    }

    const loginBtn = document.getElementById('login-btn'); // Get the login button element
    loginBtn.addEventListener('mouseover', () => { // Add mouseover event listener
        loginBtn.style.transform = 'scale(1.05)'; // Scale up the button on hover
    });

    loginBtn.addEventListener('mouseout', () => { // Add mouseout event listener
        loginBtn.style.transform = 'scale(1)'; // Reset the button scale on mouse out
    });
</script>
</body>
</html>
