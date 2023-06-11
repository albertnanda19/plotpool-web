<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    echo 'error';
    exit();
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Check if a photo is uploaded
if (isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    
    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo 'error';
        exit();
    }
    
    // Generate unique file name for the photo
    $fileName = uniqid() . '.jpg';
    
    // Path where the photo will be saved
    $filePath = 'profile_photos' . $fileName;
    
    // Save the photo to the file system
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Connect to the database
        $koneksi = mysqli_connect("localhost", "root", "", "database");

        // Check the database connection
        if (mysqli_connect_errno()) {
            echo 'error';
            exit();
        }
        
        // Prepare the query to update the profile photo
        $query = "UPDATE users SET profile_photo = '$fileName' WHERE username = '$username'";
        
        // Execute the query
        $result = mysqli_query($koneksi, $query);
        
        // Check if the update is successful
        if ($result) {
            echo 'success';
        } else {
            echo 'error';
        }
        
        // Close the database connection
        mysqli_close($koneksi);
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
