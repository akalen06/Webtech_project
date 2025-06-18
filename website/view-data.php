<!DOCTYPE html>
<html>
<head>
    <title>Temperatuur Data Overzicht</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Temperatuur Data Overzicht</h1>

    <?php
    include 'db_connection.php'; // Includeer je database connectie bestand

    $temperatures = []; // Array om data voor JavaScript in op te slaan

    try {
        $stmt = $conn->prepare("SELECT id, sensor_name, temperature, reading_time FROM temperatures ORDER BY reading_time ASC"); // LET OP: ORDER BY ASC voor grafiek
        $stmt->execute();

        // Controleer of er resultaten zijn
        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Sensor Naam</th><th>Temperatuur (°C)</th><th>Tijdstip</th></tr>";
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row["id"]. "</td>";
                echo "<td>" . $row["sensor_name"]. "</td>";
                echo "<td>" . $row["temperature"]. "</td>";
                echo "<td>" . $row["reading_time"]. "</td>";
                echo "</tr>";

                // Voeg data toe aan de $temperatures array voor de grafiek
                $temperatures[] = [
                    'sensor_name' => $row['sensor_name'],
                    'temperature' => (float)$row['temperature'], // Cast naar float voor JS
                    'reading_time' => $row['reading_time']
                ];
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

    <h2>Temperatuur Grafiek</h2>
    <div style="width: 80%; margin: auto;">
        <canvas id="temperatureChart"></canvas>
    </div>

    <p><a href="index.html">Terug naar Home</a></p>

    <script>
        // Converteer PHP data naar JavaScript
        const phpData = <?php echo json_encode($temperatures); ?>;

        // Voorbereiden van data voor Chart.js
        const labels = phpData.map(item => `<span class="math-inline">\{item\.sensor\_name\} \(</span>{new Date(item.reading_time).toLocaleTimeString()})`); // Label: Sensor (Tijd)
        const data = phpData.map(item => item.temperature);

        const ctx = document.getElementById('temperatureChart').getContext('2d');
        const temperatureChart = new Chart(ctx, {
            type: 'line', // Of 'bar' voor een staafgrafiek
            data: {
                labels: labels,
                datasets: [{
                    label: 'Temperatuur (°C)',
                    data: data,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Temperatuurverloop per Sensor'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Temperatuur (°C)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Sensor en Tijd'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
