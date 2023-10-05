<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temperature = isset($_POST['temperature']) ? $_POST['temperature'] : null;
    $humidity = isset($_POST['humidity']) ? $_POST['humidity'] : null;
    $waterLevel = isset($_POST['waterLevel']) ? $_POST['waterLevel'] : null; // Добавляем уровень воды

    $servername = "localhost";
    $username = "icoral";
    $password = "wjqthr12!";
    $dbname = "icoral";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO sensor_data (temperature, humidity, waterLevel) VALUES (?, ?, ?)"; // Обновляем запрос SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddd", $temperature, $humidity, $waterLevel); // Обновляем параметры

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


<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            font-family: Arial, sans-serif;
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

        h1 {
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .refresh-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sensor Data</h1>
        <button class="refresh-button" onclick="refreshData()">Refresh Data</button>
        <table>
            <tr>
                <th>ID</th>
                <th>Temperature</th>
                <th>Humidity</th>
                <th>Water Level</th>
                <th>Timestamp</th>
            </tr>
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
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["id"] . "</td><td>" . $row["temperature"] . "</td><td>" . $row["humidity"] . "</td><td>" . $row["waterLevel"] . "</td><td>" . $row["timestamp"] . "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No data available</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
    <script>
        function refreshData() {
            location.reload();
        }
    </script>
</body>
</html>
