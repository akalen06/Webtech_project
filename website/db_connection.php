<?php

$servername = "db"; // De servicenaam van de database in docker-compose.yml
$username = "root"; // Jouw database gebruikersnaam
$password = "wachtwoord123"; // Jouw MYSQL_ROOT_PASSWORD
$dbname = "sensordata"; // Jouw MYSQL_DATABASE

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Stel de PDO error mode in op exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Verbinding succesvol gemaakt<br>"; // Alleen voor testen, later verwijderen

} catch(PDOException $e) {
    echo "Verbindingsfout: " . $e->getMessage();
    die(); // Stop de scriptuitvoering bij een fout
}

?>
