<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
        // Redirect to the login page
        header("Location: ../index.html");
        exit();
    }

    // Retrieve the username from the session
    $username = $_SESSION['username'];

    // Connect to the database
    $koneksi = mysqli_connect("localhost", "root", "", "database");

    // Check the database connection
    if (mysqli_connect_errno()) {
        echo "Koneksi database gagal: " . mysqli_connect_error();
        exit();
    }

    // Prepare the query to retrieve the profile picture path
    $query = "SELECT profile_photo FROM users WHERE username = '$username'";

    // Execute the query
    $result = mysqli_query($koneksi, $query);

    // Check if the query is successful and if a profile picture exists
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $profilePicture = $row['profile_photo'];

        // Delete the profile picture from the database
        $deleteQuery = "UPDATE users SET profile_photo = '' WHERE username = '$username'";
        $deleteResult = mysqli_query($koneksi, $deleteQuery);

        // Delete the profile picture file from the folder
        if ($deleteResult && !empty($profilePicture)) {
            $filePath = realpath($profilePicture);
            if (is_writable($filePath) && unlink($filePath)) {
                echo "Foto profil berhasil dihapus.";
            } else {
                echo "Terjadi kesalahan saat menghapus foto profil.";
            }
        } else {
            echo "Terjadi kesalahan saat menghapus foto profil dari database.";
        }
    } else {
        echo "Foto profil tidak ditemukan.";
    }

    // Close the database connection
    mysqli_close($koneksi);
?>
