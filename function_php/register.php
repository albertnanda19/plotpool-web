<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Validate form data
  if (empty($email) || empty($username) || empty($password)) {
    echo "Please fill in all the fields.";
  } else {
    // Connect to MySQL
    $conn = new mysqli("localhost", "root", "", "database");

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Check if email or username already exists
    $sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Email or username already exists
      echo "Email or username already exists.";
    } else {
      // Save user data to database
      $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
      if ($conn->query($sql) === TRUE) {
        // User data saved successfully
        header('Location: ../sing-up/clone-page/index.html');
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }

    // Close database connection
    $conn->close();
  }
}
?>
