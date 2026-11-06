<?php
include("database.php");
?>

<!DOCTYPE html>

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
        <h3>Sign up</h3>

        <form action="process_registration.php" method="post">
            <table>
                <tr>
                    <td>idAhli:</td>
                    <td>
                    <input type="text" name="idAhli" placeholder="psmksk123" required>
                    </td>
                </tr>

                <tr>
                    <td>Nama:</td>
                    <td>
                        <input type="text" name="Nama" required>
                    </td>
                </tr>

                <tr>
                    <td>Password:</td>
                    <td>
                        <input type="password" name="Password" placeholder="Maximum 10 Perkataan" required>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Daftar">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>


<?php

include("database.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idAhli = $_POST["idAhli"];
    $Nama= $_POST["Nama"];
    $PasswordAhli = $_POST["PasswordAhli"];

}

    $sql_1 = "INSERT INTO ahli 
    VALUES ('$idAhli', '$Nama','$PasswordAhli')";

try {
    mysqli_query($conn, $sql_1);
    header("Location: signup.php");
    exit();
/** @var \App\Models\User $user **/
mysqli_close($conn);
}
?>
