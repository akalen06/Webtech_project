<?php
include 'db_connection.php'; // Includeer je database connectie bestand

try {
    $sql = "CREATE TABLE IF NOT EXISTS temperatures (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sensor_name VARCHAR(50) NOT NULL,
        temperature DECIMAL(4,1) NOT NULL,
        reading_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    $conn->exec($sql);
    echo "Tabel 'temperatures' succesvol aangemaakt (of bestond al).<br>";

} catch(PDOException $e) {
    echo "Fout bij aanmaken tabel: " . $e->getMessage();
}

$conn = null; // Sluit de verbinding
?>
