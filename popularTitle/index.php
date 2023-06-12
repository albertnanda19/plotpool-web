<?php 
    session_start();

    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header("Location: ../index.html");
        exit();
    }

    $username = $_SESSION['username'];

    $koneksi = new mysqli("localhost", "root", "", "database");

    if(isset($_GET["id"]))
    {
        $id = $_GET["id"];

        if($koneksi->connect_error)
        {
            die("Koneksi gagal: ".$koneksi->connect_error);
        }

        $sql = "SELECT * FROM novel WHERE id = '$id'";
        $hasil = $koneksi->query($sql);

        if($hasil->num_rows > 0)
        {
            $row = $hasil->fetch_assoc();
            $judul = $row["judul"];
            $sampul = $row["sampul"];
            $genre = $row["genre"];
            $rating_usia = $row["rating_usia"];
            $tahun_terbit = $row["tahun_terbit"];
            $sinopsis = $row["sinopsis"];
            $file_pdf = $row["file_pdf"];
        }
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

    if ($hasil_status) {
        $row_status = mysqli_fetch_assoc($hasil_status);
        $status = $row_status['status'];
    } else {
        $response = array('error' => 'Failed to retrieve status: ' . mysqli_error($koneksi));
        echo json_encode($response);
    }

    mysqli_close($koneksi);
?>



<!DOCTYPE html>
<html lang="en">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="script.js"></script>

<head>
    <link rel="stylesheet" href="dist/sweetalert2.min.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlotPool</title>
    <link rel="stylesheet" href="infoNovelstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Nunito:ital,wght@0,400;0,700;1,400&family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="img/logo-shortcut.png" >
</head>
<body>
    <script src="script.js"></script>
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
            <?php
                $admin = "adminplotpool"; 
                if ($_SESSION['username'] === $admin) {
                    ?>
                    <li>
                        <a href="../upload_novel/index.php">
                            <i class="bx bx-cloud-upload"></i>
                            <span class="nama-menu">Uploads</span>
                        </a>
                    </li>
                    <?php
                }
            ?>
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
                        <div class="nama" id="username-id"><?php echo $username; ?></div>
                        <div class="status"><?php echo $status; ?></div>
                    </div>
                </div>
                <i class='bx bx-log-out cursor-pointer' id="log-out" onclick="logOut()"></i>
            </div>
        </div>    
    </div>

    <div class="halaman-review">
        <section class="gambaran-novel">
            <div class="novel-content ">
                <!-- <img src="img/popular-title-novel-bg-1.jpeg" alt="" class="novel-review-bg">  -->
                <figure class="novel-img">
                    <img src="sampul/popular-title.jpg" alt="No Image">
                </figure>
                <div class="info-detail-novel">
                    <div class="info-novel">
                        <h1 class="h1 judul-novel">Bumi Manusia</h1>

                        <div class="detail-rating">
                            <div class="info-rating">
                                <div class="rating rating-fill">PG 13</div>
                            </div>

                            <div class="info-genre">
                                <a href="#">Drama Sejarah</a>
                            </div>

                            <div class="tahun-rilis">
                                <div>
                                    <ion-icon name="calender-outline" ></ion-icon>
                                    <time datetime="2021">1980</time>
                                </div>
                            </div>
                        </div>
                        <p class="sinopsis">
                            Bumi Manusia adalah sebuah novel fiksi dengan genre drama history yang memiliki setting di kehidupan periode penjajahan Belanda. Dalam buku ini, dikisahkan pula kehidupan seorang pemuda Pribumi bernama Minke. Minke bersekolah di H.B.S atau Hogere Burgerschool, yaitu setingkat dengan Sekolah Menengah Akhir (SMA) dan hanya diperuntukan bagi orang Eropa, Belanda, dan Elite Pribumi.
                        </p>
                        <div class="detail-action">
                            <button class="share">
                                <a href="#">
                                    <ion-icon name="share-social"></ion-icon>
                                    <span>Share</span>
                                </a>
                            </button>
                        </div>

                        <a href="https://archive.org/download/pramoedyaanantatoerbumimanusiahastamitra/Pramoedya%20Ananta%20Toer-Bumi%20Manusia-Hasta%20Mitra.pdf" target="_blank" class="tombol-download">
                            <ion-icon name="download-outline"></ion-icon>
                            <span>Download</span>
                        </a>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../../function_js/script.js"></script>
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