<?php

// Establish the database connection
$connection = mysqli_connect('localhost', 'root', 'foudror245', 'book_db');

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['send'])) {
    // Sanitize form data
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $location = mysqli_real_escape_string($connection, $_POST['location']);
    $guests = mysqli_real_escape_string($connection, $_POST['guests']);
    $departure = mysqli_real_escape_string($connection, $_POST['departure']);
    $arrival = mysqli_real_escape_string($connection, $_POST['arrival']);

    // Prepare SQL statement
    $stmt = $connection->prepare("INSERT INTO book_form (name, email, phone, address, location, guests, departure, arrival) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("ssssssss", $name, $email, $phone, $address, $location, $guests, $departure, $arrival);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Get the last inserted ID
        $last_id = $connection->insert_id;

        // Redirect to verification page with the booking ID
        header("Location: verify_booking.php?id=" . $last_id);
        exit();
    } else {
        echo "Error inserting data: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo 'Form data not received.';
}

// Close the database connection
mysqli_close($connection);

?>
