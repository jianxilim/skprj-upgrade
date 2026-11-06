<?php
session_start();
include("connect.php"); // Include file with database connection

if(isset($_POST["submit"])) {
    $namajadual = $_POST["namajadual"];
    $namafail = $_FILES["namafail"]["name"];
    $sementara = $_FILES["namafail"]["tmp_name"];
    move_uploaded_file($sementara, $namafail);
    
    $fail = fopen($namafail, "r");

    while(!feof($fail)) {
        $medan = explode(",", fgets($fail));
        $berjaya = false;

        if(strtolower($namajadual) === "ahli") {
            $idAhli = trim($medan[0]);
            $Nama = trim($medan[1]);
            $PasswordAhli = trim($medan[2]);
    
           // Insert into ahli table
           $sql = "INSERT INTO ahli (idAhli, Nama, PasswordAhli)
           VALUES ('$idAhli','$Nama', '$PasswordAhli')";

 // Execute queries
 if(mysqli_query($conn, $sql)) {
     $berjaya = true;
 } else {
     echo '<span class="error">Ralat: ' . mysqli_error($conn) . '</span>';
 }
}


if(strtolower($namajadual) === "guru") {
 $idGuru = trim($medan[0]);
 $NamaGuru = trim($medan[1]);
 $PasswordGuru = trim($medan[2]);



 // Insert into guru table
 $sql = "INSERT INTO guru (idGuru, NamaGuru, PasswordGuru)
           VALUES ('$idGuru','$NamaGuru', '$PasswordGuru')";

 // Execute queries
 if(mysqli_query($conn, $sql)) {
     $berjaya = true;
 } else {
     echo '<span class="error">Ralat: ' . mysqli_error($conn) . '</span>';
 }
}

}

fclose($fail);

// Alert based on success or failure
if($berjaya) {
echo "<script>alert('Rekod berjaya di import');</script>";
} else {
echo "<script>alert('Rekod tidak berjaya di import');</script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_guru.php"); ?>
    <h1>Import Data</h1>
    <div class="form">
        <form action="importdata_guru.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="warna" for="jadual">Jadual:</label>
                <select name="namajadual" id="jadual">
                    <option value="Ahli">Ahli</option>
                    <option value="Guru">Guru</option>
                </select>
            </div>
            <div class="form-group">
                <label class="warna" for="namafail">Nama Fail:</label>
                <input type="file" name="namafail" id="namafail" accept=".txt" required>
            </div>
            <div class="button-container">
                <button class="import" type="submit" name="submit">Import</button>
            </div>
        </form>

        <span class="format">Format: idAhli, Nama, PasswordAhli / idGuru, NamaGuru, PasswordGuru </span>
    </div>

</body>
</html>
