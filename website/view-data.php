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

    // ----------------------------------------------------
    // NIEUW: Logica om verwijderverzoek te verwerken (DELETE operatie)
    // ----------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
        $id_to_delete = $_POST['delete_id'];
        try {
            // Gebruik prepared statements om SQL injecties te voorkomen
            $stmt_delete = $conn->prepare("DELETE FROM temperatures WHERE id = :id");
            $stmt_delete->bindParam(':id', $id_to_delete, PDO::PARAM_INT); // Bind de parameter als integer
            $stmt_delete->execute();
            echo "<p style='color: green;'>Record met ID " . htmlspecialchars($id_to_delete) . " succesvol verwijderd.</p>";
            // Optioneel: herlaad de pagina om de wijziging direct te tonen (kan ook via JavaScript)
            // header("Location: view-data.php"); // Herleidt de pagina, verbergt de echo
            // exit();
        } catch(PDOException $e) {
            echo "<p style='color: red;'>Fout bij verwijderen record: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    // ----------------------------------------------------
    // EINDE NIEUW: Logica om verwijderverzoek te verwerken
    // ----------------------------------------------------

    $temperatures = []; // Array om data voor JavaScript in op te slaan

    try {
        // Pas de query aan om de data voor de grafiek in oplopende volgorde op te halen
        $stmt = $conn->prepare("SELECT id, sensor_name, temperature, reading_time FROM temperatures ORDER BY reading_time ASC");
        $stmt->execute();

        // Controleer of er resultaten zijn
        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Sensor Naam</th><th>Temperatuur (°C)</th><th>Tijdstip</th><th>Acties</th></tr>"; // NIEUW: Acties kolom
            // Output data van elke rij
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]). "</td>"; // htmlspecialchars voor XSS-preventie
                echo "<td>" . htmlspecialchars($row["sensor_name"]). "</td>";
                echo "<td>" . htmlspecialchars($row["temperature"]). "</td>";
                echo "<td>" . htmlspecialchars($row["reading_time"]). "</td>";
                echo "<td>"; // NIEUW: Acties cel
                // Formulier voor verwijderknop (gebruikt POST om ID te sturen)
                echo "<form method='post' style='display:inline;'>";
                echo "<input type='hidden' name='delete_id' value='" . htmlspecialchars($row["id"]) . "'>";
                // Minimale inline styling voor de knop
                echo "<button type='submit' style='background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;'>Verwijder</button>";
                echo "</form>";
                // Optioneel: Bewerk knop (implementeren we later)
                // echo " <a href='edit_data.php?id=" . htmlspecialchars($row["id"]) . "' style='background-color: #007bff; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; text-decoration: none; margin-left: 5px;'>Bewerk</a>";
                echo "</td>"; // EINDE Acties cel
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
        echo "Fout bij ophalen data: " . htmlspecialchars($e->getMessage());
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
