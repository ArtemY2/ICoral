<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
    <style>
    
body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

.container {
    max-width: 800px;
    margin: 0 auto;
}

.header {
    background-color: #333;
    color: white;
    padding: 10px;
    text-align: center;
}

.content {
    padding: 20px;
}

.success {
    color: green;
}

.error {
    color: red;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Data Insertion</h1>
        </div>
        <div class="content">
            <?php
            if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
                echo 'We don\'t have mysqli!!!';
            } else {
                echo 'Phew we have it!';
            }

            $servername = "localhost";
            $username = "icoral";
            $password = "wjqthr12!";
            $dbname = "icoral";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $post_data = file_get_contents('php://input');
                if ($post_data !== false && !empty($post_data)) {
                    $data = json_decode($post_data, true);

                    $temperature = isset($data['temperature']) ? $data['temperature'] : null;
                    $humidity = isset($data['humidity']) ? $data['humidity'] : null;
                    $waterLevel = isset($data['waterLevel']) ? $data['waterLevel'] : null;

                    $sql = "INSERT INTO sensor_data (temperature, humidity, waterLevel) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ddd", $temperature, $humidity, $waterLevel);
                    if ($stmt->execute() === TRUE) {
                        echo "<p class='success'>Данные успешно вставлены в базу данных.</p>";
                    } else {
                        echo "<p class='error'>Ошибка вставки данных: " . $conn->error . "</p>";
                    }
                    $stmt->close();
                } else {
                    echo '<p class="error">Пустое тело POST-запроса.</p>';
                }
            } else {
                echo '<p class="error">Неверный метод запроса.</p>';
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
