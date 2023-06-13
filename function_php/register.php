<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($email) || empty($username) || empty($password)) {
      echo "Please fill in all the fields.";
    } else {
      $conn = new mysqli("localhost", "root", "", "database");

      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        echo "Email atau username sudah terdaftar.";
      } else {
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        if ($conn->query($sql) === TRUE) {
          header('Location: ../sign-up/clone-page/index.html');
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
      }

      $conn->close();
    }
  }
?>
