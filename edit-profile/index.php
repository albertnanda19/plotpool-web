<?php
    session_start();

    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        header("Location: ../index.html");
        exit();
    }

    $username = $_SESSION['username'];

    function deleteProfilePictureFile($filePath) {
        if (file_exists($filePath)) {
            // Delete the file
            if (unlink($filePath)) {
                echo <<<HTML
                    <script>
                        alert
                    </script>
                HTML
                ;
            } else {
                echo "Gagal menghapus foto profil.";
            }
        } else {
            echo "File foto profil tidak ditemukan.";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_picture'];
            $fileName = $file['name'];
            $fileTmpPath = $file['tmp_name'];

            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $newFileName = uniqid('', true) . '.' . $fileExtension;

            $uploadDirectory = '../profile_photos/';

            $uploadFilePath = $uploadDirectory . $newFileName;
            $imageSize = getimagesize($fileTmpPath);
            if ($imageSize === false) {
                echo 'File yang diunggah bukan gambar.';
                exit();
            }

            if ($file['size'] > 5 * 1024 * 1024) {
                echo 'Ukuran file terlalu besar.';
                exit();
            }

            $allowedFormats = ['jpg', 'jpeg', 'png'];
            if (!in_array($fileExtension, $allowedFormats)) {
                echo 'Hanya file dengan format JPG, JPEG, atau PNG yang diperbolehkan.';
                exit();
            }

            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                $koneksi = mysqli_connect("localhost", "root", "", "database");
                if (mysqli_connect_errno()) {
                    echo "Koneksi database gagal: " . mysqli_connect_error();
                    exit();
                }

                $query = "SELECT profile_photo FROM users WHERE username = '$username'";

                $result = mysqli_query($koneksi, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $profilePicture = $row['profile_photo'];

                    if (!empty($profilePicture)) {
                        deleteProfilePictureFile($profilePicture);
                    }
                }

                $query = "UPDATE users SET profile_photo = '$uploadFilePath' WHERE username = '$username'";
                $result = mysqli_query($koneksi, $query);
                if ($result) {
                    echo "Foto profil berhasil diperbarui.";
                } else {
                    echo "Terjadi kesalahan: " . mysqli_error($koneksi);
                }

                mysqli_close($koneksi);
            } else {
                echo 'Terjadi kesalahan saat mengunggah file.';
            }
        }

        if (isset($_POST['submit-status'])) {
            $status = $_POST['user-status'];

            $koneksi = mysqli_connect("localhost", "root", "", "database");

            if (mysqli_connect_errno()) {
                echo "Koneksi database gagal: " . mysqli_connect_error();
                exit();
            }

            $query = "UPDATE users SET status = '$status' WHERE username = '$username'";

            $result = mysqli_query($koneksi, $query);

            if ($result) {
                echo "Status berhasil diperbarui.";
            } else {
                echo "Terjadi kesalahan: " . mysqli_error($koneksi);
            }

            mysqli_close($koneksi);
        }

        if (isset($_POST['delete-picture'])) {
            $koneksi = mysqli_connect("localhost", "root", "", "database");

            if (mysqli_connect_errno()) {
                echo "Koneksi database gagal: " . mysqli_connect_error();
                exit();
            }

            $query = "SELECT profile_photo FROM users WHERE username = '$username'";

            $result = mysqli_query($koneksi, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $profilePicture = $row['profile_photo'];

                if (!empty($profilePicture)) {
                    deleteProfilePictureFile($profilePicture);
                }

                $query = "UPDATE users SET profile_photo = '' WHERE username = '$username'";

                $result = mysqli_query($koneksi, $query);

                if ($result) {
                    echo "Foto profil berhasil dihapus.";
                } else {
                    echo "Terjadi kesalahan: " . mysqli_error($koneksi);
                }
            }

            mysqli_close($koneksi);
        }
    }
?>

<?php
    $username = $_SESSION['username'];
    $koneksi = mysqli_connect("localhost", "root", "", "database");
    if (mysqli_connect_errno()) {
        echo "Koneksi database gagal: " . mysqli_connect_error();
        exit();
    }
    $query = "SELECT profile_photo FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $profilePicture = $row['profile_photo'];
    } else {
            $profilePicture = '';
    }

    $query_status = "SELECT status FROM users WHERE username = '$username'";
    $result_status = mysqli_query($koneksi, $query_status);

    if ($result_status) {
        $row = mysqli_fetch_assoc($result_status);
        $status = $row['status'];
    } else {
        $response = array('error' => 'Gagal mendapatkan status: ' . mysqli_error($koneksi));
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
        <script src="../function_js/script.js"></script>
        <link rel="stylesheet" href="editProfile.css">
        <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Nunito:ital,wght@0,400;0,700;1,400&family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="shortcut icon" href="img/logo-shortcut.png" >
    </head>
    <body>
        <div class="halaman-info">
            <div class="container">
                    <div class="profile-picture">
                        <?php
                            if (isset($profilePicture) && !empty($profilePicture)) {
                                echo '<img id="foto-profil" src="' . $profilePicture . '" alt="" class="foto-profil">';
                            } else {
                                echo '<img id="foto-profil" src="img/no-profile.png" alt="" class="foto-profil">';
                            }
                        ?>
                    </div>
                <form method="POST" enctype="multipart/form-data" id="profile-picture-form">
                    <input type="file" id="profile-picture-input" name="profile_picture" onchange="handleFileInput(event)">
                    <button type="submit" name="submit-picture">Simpan Foto Profil</button>
                    <button type="submit" name="delete-picture">Hapus Foto Profil</button>
                </form>
                <div class="user-name">
                    <p><span id="side-nama-user" class="nama-user"></span></p><br>
                    <!-- <input type="text" id="kirim-status" name="user-status"> -->
                </div>
                <form method="POST" id="status-form">
                    <div class="user-status">
                        <input type="text" id="kirim-status" name="user-status" placeholder="Status">
                        <button type="submit" name="submit-status">Ganti Status</button>
                    </div>
                </form>
            </div>
        </div>
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
                        <!-- <img src="img/no-profile.png" alt="img/no-profile.png"> -->
                        <?php
                            if (isset($profilePicture) && !empty($profilePicture)) {
                                echo '<img id="foto-profil" src="../profile_photos/' . $profilePicture . '" alt="" class="foto-profil">';
                            } else {
                                echo '<img id="foto-profil" src="img/no-profile.png" alt="" class="foto-profil">';
                            }
                        ?>
                        <div class="nama-user">
                            <div class="nama" id="username-id"><div class="nama"><?php echo $username; ?></div></div>
                            <div class="status"><?php echo $status; ?></div>
                        </div>
                    </div>
                    <i class='bx bx-log-out cursor-pointer' id="log-out" onclick="logout()"></i>
                </div>
            </div>  
        </div>

        
        
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <script>
            function handleFileInput(event) {
                const file = event.target.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.getElementById('foto-profil');
                    img.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
            function direct()
            {
                window.location.href = '../edit-profile/index.php';
            }
        </script>
    </body>
</html>
