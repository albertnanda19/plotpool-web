<?php
    session_start();

    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header("Location: ../index.html");
        exit();
    }

    $username = $_SESSION['username'];
    $koneksi = mysqli_connect("localhost", "root", "", "database");
    if (mysqli_connect_errno()) {
        echo "Koneksi database gagal: " . mysqli_connect_error();
        exit();
    }
    $query_profilePicture = "SELECT profile_photo FROM users WHERE username = '$username'";
    $hasil_profilePicture = mysqli_query($koneksi, $query_profilePicture);
    
    if($hasil_profilePicture && mysqli_num_rows($hasil_profilePicture) > 0)
    {
        $row_profilePicture = mysqli_fetch_assoc($hasil_profilePicture);
        $profilePicture = $row_profilePicture['profile_photo']; 
    }else{
        $profilePicture = '';
    }

    $query_status = "SELECT status FROM users WHERE username = '$username'";
    $hasil_status = mysqli_query($koneksi, $query_status);

    if($hasil_status)
    {
        $row_status = mysqli_fetch_assoc($hasil_status);
        $status = $row_status['status'];
    }else{
        $response = array('error' => 'Gagal menampilkan status: '.mysqli_error($koneksi));
        echo json_encode($response);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if ($koneksi->connect_error) {
            die("Koneksi gagal: " . $koneksi->connect_error);
        }

        $judul = $_POST["judul"];
        $penulis = $_POST["penulis"];
        $sampul = $_FILES["sampul"]["name"];
        $genre = $_POST["genre"];
        $rating_usia = $_POST["rating_usia"];
        $tahun_terbit = $_POST["tahun_terbit"];
        $sinopsis = $_POST["sinopsis"];
        $file_pdf = $_POST["file_pdf"];

        $targetDir = "../info_novel/sampul/";
        $targetFile = $targetDir . basename($sampul);
        move_uploaded_file($_FILES["sampul"]["tmp_name"], $targetFile);

        $sql = "INSERT INTO novel (judul, penulis, sampul, genre, rating_usia, tahun_terbit, sinopsis, file_pdf)
                VALUES ('$judul', '$penulis', '$sampul','$genre', '$rating_usia', $tahun_terbit, '$sinopsis', '$file_pdf')";

        if ($koneksi->query($sql) === TRUE) {
            echo "Data novel berhasil disimpan.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $koneksi->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlotPool</title>
    <link rel="stylesheet" href="uploadNovel.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Nunito:ital,wght@0,400;0,700;1,400&family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="img/logo-shortcut.png" >
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-logo">
            <div class="logo">
                <img class="gambar-logo" src="img/logo.png" alt="">
                <div class="nama-logo">PlotPool</div>
            </div>
        </div>

        <ul class="daftar-menu">
            <li class="pencarian">
                <i class="bx bx-search"></i>
                <form id="searchForm" action="../titles-page/index.php" method="get">
                    <input type="text" placeholder="Search Novel...">
                </form>
            </li>
            <li>
                <a href="../home/index.php">
                    <i class='bx bx-home'></i>
                    <span class="nama-menu" onclick="toHome()">Home</span>
                </a>
            </li>
            <li>
                <a href="../titles-page/index.php">
                    <i class="bx bx-book-open"></i>
                    <span class="nama-menu" onclick="toTitles()">Title</span>
                </a>
            </li>
            <li>
                <?php 
                    $koneksi = new mysqli("localhost", "root", "", "database");

                    if($koneksi->connect_error)
                    {
                        die("Koneksi gagal: " . $koneksi->connect_error);
                    }

                    $query = "SELECT id FROM novel";
                    $result = $koneksi->query($query);

                    if($result)
                    {
                        if($result->num_rows > 0)
                        {
                            $novelID = [];

                            while ($row = $result->fetch_assoc())
                            {
                                $novelID[] = $row['id'];
                            }

                            $result->free();

                            shuffle($novelID);

                            $randomNovelID = $novelID[0];

                            echo '<a href="../info_novel/index.php?id=' . $randomNovelID . '">';
                            echo '<i class="bx bx-crosshair"></i>';
                            echo '<span class="nama-menu">Random</span>';
                            echo '</a>';
                        }
                    }else{
                        echo "<p>Terjadi kesalahan saat menjalankan query" . $koneksi->error . "</p>";
                    }

                    $koneksi->close();
                ?>
            </li>
            <li>
                <a href="../about-developers-page/index.php">
                    <i class="fa fa-info"></i>
                    <span class="nama-menu">About Us</span>
                </a>
            </li>
        </ul>

        <div class="info-profil">
            <div class="profil">
                <div class="detail-profil">
                    <?php
                        if (isset($profilePicture) && !empty($profilePicture)) {
                            echo '<img id="foto-profil" src="' . $profilePicture . '" onclick="direct()" alt="" class="foto-profil">';
                        } else {
                            echo '<img id="foto-profil" src="img/no-profile.png" onclick="direct()" alt="" class="foto-profil">';
                        }
                    ?>
                    <div class="nama-user">
                        <div class="nama" id="username-id"><div class="nama"><?php echo $username; ?></div></div>
                        <div class="status"><?php echo $status; ?></div>
                    </div>
                </div>
                <i class='bx bx-log-out cursor-pointer' id="log-out" onclick="logout()" ></i>
            </div>
        </div>
    </div>

    <div class="halaman-home">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <div class="container">
                <h2>Input Novel</h2>
                
                <div class="input-box">
                    <label for="judul">Judul:</label><br>
                    <input type="text" id="judul" name="judul" required=""><br><br>
                </div>
                
                <div class="input-box">
                    <label for="penulis">Nama Penulis:</label><br>
                    <input type="text" id="penulis" name="penulis" required=""><br><br>
                </div>

                <div class="input-box">
                    <label class for="sampul">Sampul:</label><br>
                    <input type="file" id="sampul" name="sampul" required=""><br><br>
                </div>

                <div class="input-box">
                    <label for="genre">Genre:</label><br>
                    <input type="text" id="genre" name="genre" required=""><br><br>
                </div>

                <div class="input-box">
                    <label for="rating_usia">Rating Usia:</label><br>
                    <select class="rating-usia" id="rating_usia" name="rating_usia" required="">
                        <option value="">Pilih Rating Usia</option>
                        <option value="G">G (General)</option>
                        <option value="PG">PG (Parental Guidance Suggested)</option>
                        <option value="PG-13">PG-13 (Parents Strongly Cautioned)</option>
                        <option value="R">R (Restricted)</option>
                        <option value="NC-17">NC-17 (Adults Only)</option>
                    </select><br><br>
                </div>

                <div class="input-box">
                    <label for="tahun_terbit">Tahun Terbit:</label><br>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" required=""><br><br>
                </div>

                <div class="input-box">
                    <label for="sinopsis">Sinopsis:</label><br>
                    <textarea id="sinopsis" name="sinopsis" required=""></textarea><br><br>
                </div>

                <div class="input-box">
                    <label for="file_pdf">Link Download PDF:</label><br>
                    <input type="text" id="file_pdf" name="file_pdf" required=""><br><br>
                </div>
                
                <!-- <input type="submit" value="Simpan"> -->
                <button type="submit">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="../function_js/script.js"></script>
    <script>
        function direct()
        {
            window.location.href = '../edit-profile/index.php';
        }

        const searchForm = document.getElementById('searchForm');
        const searchInput = searchForm.querySelector('input[type="text"]');

        searchForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const keyword = searchInput.value.trim();
            if (keyword !== '') {
                window.location.href = `../titles-page/index.php?search=${encodeURIComponent(keyword)}`;
            }
        });
    </script>
</body>
</html>