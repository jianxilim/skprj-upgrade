<?php
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kehadiran Kelab E-sport</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include("header_guru.php"); ?>
    <main>
        <h3 class="pendek">Pilih Jenis Laporan</h3>
        <form class="pendek" action="laporan2_guru.php" method="post">
            <select id="pilih" name="pilih" onchange="paper_pilihan()">
                <option value="1">Senarai Mengikut Aktiviti</option>
                <option value="2">Senarai Mengikut Ahli</option>
            </select><br>
            <div id="aktiviti" style="display: block;">
                <select name="idAktiviti">
                    <?php
                    $sql = "SELECT * FROM aktiviti";
                    $data = mysqli_query($conn, $sql);
                    while ($aktiviti = mysqli_fetch_array($data)) {
                        echo "<option value='" . htmlspecialchars($aktiviti['idAktiviti']) . "'>" . htmlspecialchars($aktiviti['Tajuk']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div id="ahli" style="display: none;">
                <select name="idAhli">
                    <?php
                    $sql = "SELECT * FROM ahli";
                    $data = mysqli_query($conn, $sql);
                    while ($ahli = mysqli_fetch_array($data)) {
                        echo "<option value='" . htmlspecialchars($ahli['idAhli']) . "'>" . htmlspecialchars($ahli['Nama']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button class="paper" name="submit">Papar</button>
        </form>
    </main>
    <script>
        function paper_pilihan() {
            var pilih = document.getElementById("pilih").value;
            var paparaktiviti = 'none';
            var paparahli = 'none';

            if (pilih == 1) {
                paparaktiviti = 'block';
                paparahli = 'none';
            } else if (pilih == 2) {
                paparaktiviti = 'none';
                paparahli = 'block';
            }

            document.getElementById('aktiviti').style.display = paparaktiviti;
            document.getElementById('ahli').style.display = paparahli;
        }
    </script>
</body>
</html>
