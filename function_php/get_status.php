<?php
    // session_start();

    // // Check if the user is logged in
    // if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    //     $response = array('error' => 'User not logged in');
    //     echo json_encode($response);
    //     exit();
    // }

    // Retrieve the username from the session
    $username = $_SESSION['username'];

    // Connect to the database
    $koneksi = mysqli_connect("localhost", "root", "", "database");

    // Check the database connection
    if (mysqli_connect_errno()) {
        $response = array('error' => 'Failed to connect to the database: ' . mysqli_connect_error());
        echo json_encode($response);
        exit();
    }

    // Retrieve the status from the database
    $query = "SELECT status FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    // Check if the query is successful
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $status = $row['status'];

        // Return the status as JSON response
        $response = array('status' => $status);
        echo json_encode($response);
    } else {
        $response = array('error' => 'Failed to retrieve status: ' . mysqli_error($koneksi));
        echo json_encode($response);
    }

    // Close the database connection
    mysqli_close($koneksi);
?>
