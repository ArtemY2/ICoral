<?php
if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
    echo 'We don\'t have mysqli!!!';
    } else {
    echo 'Phew we have it!';
   }
$servername = "localhost"; 
$username = "innoreef"; 
$password = "wjqthr12!";
$dbname = "innoreef";

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
            echo "Данные успешно вставлены в базу данных.";
        } else {
            echo "Ошибка вставки данных: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo 'Пустое тело POST-запроса.';
    }
} else {
    echo 'Неверный метод запроса.';
}

$conn->close();
?>
