<meta charset="UTF-8">
<!DOCTYPE html>
<html>
<head>
    <title>Данные с датчиков</title>
</head>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $temperature = isset($_POST['temperature']) ? $_POST['temperature'] . '°C' : 'Нет данных';
        $humidity = isset($_POST['humidity']) ? $_POST['humidity'] . '%' : 'Нет данных';
        $waterLevel = isset($_POST['waterLevel']) ? $_POST['waterLevel'] : 'Нет данных';

        echo '<h1>Данные с датчиков</h1>';
        echo '<p><strong>Температура:</strong> ' . $temperature . '</p>';
        echo '<p><strong>Влажность:</strong> ' . $humidity . '</p>';
        echo '<p><strong>Уровень воды:</strong> ' . $waterLevel . '</p>';
    } else {
        echo 'Неверный метод запроса.';
    }
    ?>
</body>
</html>
