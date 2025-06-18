<?php
include 'db_connection.php'; // Includeer je database connectie bestand

// Array met voorbeelddata
$data_to_insert = [
    ['Sensor A', 22.5],
    ['Sensor B', 20.1],
    ['Sensor A', 23.0],
    ['Sensor C', 25.7],
    ['Sensor B', 19.8]
];

try {
    $stmt = $conn->prepare("INSERT INTO temperatures (sensor_name, temperature) VALUES (:sensor_name, :temperature)");

    foreach ($data_to_insert as $data) {
        $sensor_name = $data[0];
        $temperature = $data[1];

        $stmt->bindParam(':sensor_name', $sensor_name);
        $stmt->bindParam(':temperature', $temperature);
        $stmt->execute();
        echo "Data ingevoegd: Sensor: " . $sensor_name . ", Temperatuur: " . $temperature . "<br>";
    }

    echo "Alle testdata succesvol ingevoegd!<br>";

} catch(PDOException $e) {
    echo "Fout bij invoegen data: " . $e->getMessage();
}

$conn = null; // Sluit de verbinding
?>
