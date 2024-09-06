<?php
// Establish the database connection
$connection = mysqli_connect('localhost', 'root', 'foudror245', 'book_db');

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the booking ID from the URL and validate it
$id = intval($_GET['id']);

// Fetch the booking details from the database
$stmt = $connection->prepare("SELECT * FROM book_form WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No booking found!";
    exit();
}

// Handle update or delete request
if (isset($_POST['update'])) {
    // Update the booking details
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $location = mysqli_real_escape_string($connection, $_POST['location']);
    $guests = mysqli_real_escape_string($connection, $_POST['guests']);
    $departure = mysqli_real_escape_string($connection, $_POST['departure']);
    $arrival = mysqli_real_escape_string($connection, $_POST['arrival']);

    $stmt = $connection->prepare("UPDATE book_form SET name=?, email=?, phone=?, address=?, location=?, guests=?, departure=?, arrival=? WHERE id=?");
    $stmt->bind_param("sssssissi", $name, $email, $phone, $address, $location, $guests, $departure, $arrival, $id);

    if ($stmt->execute()) {
        echo "Booking updated successfully!";
    } else {
        echo "Error updating booking: " . $connection->error;
    }
}

if (isset($_POST['delete'])) {
    // Delete the booking
    $stmt = $connection->prepare("DELETE FROM book_form WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: home.php'); // Redirect to home page after deletion
        exit();
    } else {
        echo "Error deleting booking: " . $connection->error;
    }
}

// Close the database connection
$stmt->close();
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body >

<div class="verify-booking">
    <div class="container">
        <h1>Verify Your Booking</h1>

        <form action="" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="number" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group">
                <label for="location">Destination:</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($row['location'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group">
                <label for="guests">Guests:</label>
                <input type="number" id="guests" name="guests" value="<?php echo htmlspecialchars($row['guests'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group">
                <label for="departure">Departure Date:</label>
                <input type="date" id="departure" name="departure" value="<?php echo htmlspecialchars($row['departure'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="form-group">
                <label for="arrival">Arrival Date:</label>
                <input type="date" id="arrival" name="arrival" value="<?php echo htmlspecialchars($row['arrival'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <input type="submit" name="update" value="Update Booking" class="btn">
            <input type="submit" name="delete" value="Delete Booking" class="btn">
            <a href="confirm.php?id=<?php echo $id; ?>" class="btn">Confirm Booking</a>
        </form>
    </div>
</div>
</body>
</html>

