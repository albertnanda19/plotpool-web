<?php
    session_start();

    if(!isset($_SESSION['username']) || empty($_SESSION['username']))
    {
        header("Location: ../index.html");
        exit();
    }

    $username = $_SESSION['username'];
    $koneksi = new mysqli("localhost", "root", "", "database");

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
    mysqli_close($koneksi);    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlotPool</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="about.css">
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
                <input type="text" placeholder="Search Novel...">
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
                        <div class="nama" id="username-id"><?php echo $username; ?></div>
                        <div class="status"><?php echo $status; ?></div>
                    </div>
                </div>
                <i class='bx bx-log-out cursor-pointer' id="log-out" onclick="logOut()"></i>
            </div>
        </div>  
    </div>

    <div class="halaman-info">
        <div class="anggota-container">
            <h1 class="judul-anggota">Developers</h1>
            <div class="box-list-anggota">
                <div class="list-anggota">
                    <div class="anggota">
                        <img src="img/albert.jpg" alt="" class="anggota-img">
                        <span class="nama-anggota">Ananda Albert Mangiri</span>
                        <p class="deskripsi-anggota">NIM : 210211060122</p>
                    </div>

                    <div class="anggota">
                        <img src="img/faithful.jpeg" alt="" class="anggota-img">
                        <span class="nama-anggota">Faithful Miracle A.Mandagi</span>
                        <p class="deskripsi-anggota">NIM : 210211060080</p>
                    </div>

                    <div class="anggota">
                        <img src="img/xavier.jpg" alt="" class="anggota-img">
                        <span class="nama-anggota">Francis Xavier Abraham Harahap</span>
                        <p class="deskripsi-anggota">NIM : 210211060276</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="demo-video">
            <div class="demo-title">
                <div class="demo-bar">
                    <h4>Demo Video</h4>
                </div>
            </div>
        </div>

        <div class="video-demo"></div>
            <video controls>
                <source src="img/video-demo-dummy.mp4" type="video/mp4">
            </video>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
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