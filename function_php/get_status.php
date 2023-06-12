<?php
    $username = $_SESSION['username'];

    $koneksi = mysqli_connect("localhost", "root", "", "database");

    if (mysqli_connect_errno()) {
        $response = array('error' => 'Koneksi ke database gagal: ' . mysqli_connect_error());
        echo json_encode($response);
        exit();
    }

    $query = "SELECT status FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);

        $response = array('status' => $status);
        echo json_encode($response);
    } else {
        $response = array('error' => 'Failed to retrieve status: ' . mysqli_error($koneksi));
        echo json_encode($response);
    }

    mysqli_close($koneksi);
?>
