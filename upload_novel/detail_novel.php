<!DOCTYPE html>
<html>
<head>
    <title>Detail Novel</title>
</head>
<body>
    <?php
    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        $conn = new mysqli("localhost", "root", "", "database");

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM novel WHERE id = '$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $judul = $row["judul"];
            $sampul = $row["sampul"];
            $genre = $row["genre"];
            $rating_usia = $row["rating_usia"];
            $tahun_terbit = $row["tahun_terbit"];
            $sinopsis = $row["sinopsis"];
            $file_pdf = $row["file_pdf"];
    ?>

            <h2>Detail Novel</h2>
            <h3>Judul: <?php echo $judul; ?></h3>
            <img src="sampul/<?php echo $sampul; ?>" alt="Sampul Novel"><br>
            <p>Genre: <?php echo $genre; ?></p>
            <p>Rating Usia: <?php echo $rating_usia; ?></p>
            <p>Tahun Terbit: <?php echo $tahun_terbit; ?></p>
            <p>Sinopsis: <?php echo $sinopsis; ?></p>
            <button onclick="window.location.href='<?php echo $file_pdf; ?>'">Download PDF</button> 
            
            <?php
        } else {
            echo "<p>Data novel tidak ditemukan.</p>";
        }

        // Menutup koneksi
        $conn->close();
    } else {
        echo "<p>Parameter ID tidak ditemukan.</p>";
    }
    ?>
</body>
</html>
