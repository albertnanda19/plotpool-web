<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
      echo "Please fill in all the fields.";
    } else {
      $conn = new mysqli("localhost", "root", "", "database");

      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
      $stmt->bind_param("ss", $username, $password);
      
      $stmt->execute();

      $result = $stmt->get_result();
      
      if ($result->num_rows === 1) {
        session_start();
        $_SESSION['username'] = $username;
        header("Location: ../home/index.php");
        exit;
      } else {
        echo "Invalid username or password.";
      }

      $stmt->close();
      $conn->close();
    }
  }
?>
