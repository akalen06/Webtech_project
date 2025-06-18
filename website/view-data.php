<!DOCTYPE html>
<html>
<head>
    <title>Temperatuur Data Overzicht</title>
    <link rel="stylesheet" href="style.css">
<body>
    <h1>Temperatuur Data Overzicht</h1>

    <?php
    include 'db_connection.php'; // Includeer je database connectie bestand

    try {
        $stmt = $conn->prepare("SELECT id, sensor_name, temperature, reading_time FROM temperatures ORDER BY reading_time DESC");
        $stmt->execute();

        // Controleer of er resultaten zijn
        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Sensor Naam</th><th>Temperatuur (°C)</th><th>Tijdstip</th></tr>";
            // Output data van elke rij
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row["id"]. "</td>";
                echo "<td>" . $row["sensor_name"]. "</td>";
                echo "<td>" . $row["temperature"]. "</td>";
                echo "<td>" . $row["reading_time"]. "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Geen temperatuurdata gevonden.</p>";
        }

    } catch(PDOException $e) {
        echo "Fout bij ophalen data: " . $e->getMessage();
    }

    $conn = null; // Sluit de verbinding
    ?>

    <p><a href="index.html">Terug naar Home</a></p>
</body>
</html>
