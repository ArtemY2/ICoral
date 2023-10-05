<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temperature = isset($_POST['temperature']) ? $_POST['temperature'] : null;
    $humidity = isset($_POST['humidity']) ? $_POST['humidity'] : null;

    
    $servername = "localhost";
    $username = "icoral";
    $password = "wjqthr12!";
    $dbname = "icoral";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO sensor_data (temperature, humidity) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dd", $temperature, $humidity);

    if ($stmt->execute() === TRUE) {
        echo "Data inserted successfully.";
    } else {
        echo "Error inserting data: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>



<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }
</style>

<?php
$servername = "localhost";
$username = "icoral";
$password = "wjqthr12!";
$dbname = "icoral";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sensor_data";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Temperature</th><th>Humidity</th><th>Timestamp</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["temperature"] . "</td><td>" . $row["humidity"] . "</td><td>" . $row["timestamp"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No data available";
}
$conn->close();
?>
