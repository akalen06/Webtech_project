<!DOCTYPE html>
<html>
<head>
    <title>Data Toevoegen/Bewerken</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Data Toevoegen/Bewerken</h1>

    <?php
    include 'db_connection.php'; // Includeer je database connectie bestand

    $id = null; // Variabele om bij te houden of we bewerken (ID aanwezig) of toevoegen (ID is null)
    $sensor_name = ''; // Initiele waarde voor sensor naam veld
    $temperature = ''; // Initiele waarde voor temperatuur veld
    $message = ''; // Variabele voor succes- of foutmeldingen

    // --------------------------------------------------------------------------------------------------
    // Logica voor OPHALEN van bestaande data (voor BEWERKEN, als 'id' in de URL staat)
    // --------------------------------------------------------------------------------------------------
    if (isset($_GET['id']) && $_GET['id'] != '') {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT); // Valideer het ID als een integer om veiligheid te garanderen
        if ($id === false) {
            die("Ongeldig ID opgegeven."); // Stop script als ID ongeldig is
        }

        try {
            // Bereid de query voor om data op te halen van het specifieke record
            $stmt = $conn->prepare("SELECT id, sensor_name, temperature FROM temperatures WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC); // Haal de rij op als associatieve array

            if ($row) {
                // Vul de formuliervelden met de opgehaalde data (htmlspecialchars voor XSS-preventie)
                $sensor_name = htmlspecialchars($row['sensor_name']);
                $temperature = htmlspecialchars($row['temperature']);
            } else {
                $message = "<p style='color: red;'>Record met ID " . htmlspecialchars($id) . " niet gevonden.</p>";
                $id = null; // Reset ID als record niet bestaat, zodat het formulier zich als 'toevoegen' gedraagt
            }
        } catch(PDOException $e) {
            $message = "<p style='color: red;'>Fout bij ophalen record: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    // --------------------------------------------------------------------------------------------------
    // Logica voor VERWERKEN van FORMULIER SUBMISSIE (CREATE of UPDATE)
    // --------------------------------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Trim spaties van de inputvelden
        $input_sensor_name = trim($_POST['sensor_name']);
        $input_temperature = trim($_POST['temperature']);

        // Input validatie
        if (empty($input_sensor_name)) {
            $message = "<p style='color: red;'>De sensor naam mag niet leeg zijn.</p>";
        } elseif (empty($input_temperature)) {
            $message = "<p style='color: red;'>De temperatuur mag niet leeg zijn.</p>";
        } elseif (!is_numeric($input_temperature)) { // Controleer of temperatuur een numerieke waarde is
            $message = "<p style='color: red;'>Temperatuur moet een numerieke waarde zijn.</p>";
        } else {
            // Input is geldig, nu de database-operatie uitvoeren
            // Zorg ervoor dat de waarden van de formuliervelden correct worden ingesteld na validatie
            $sensor_name = htmlspecialchars($input_sensor_name);
            $temperature = htmlspecialchars($input_temperature);

            // Controleer of er een 'id' in de POST-data zit (voor UPDATE-operatie)
            $action_id = isset($_POST['id']) && $_POST['id'] != '' ? filter_var($_POST['id'], FILTER_VALIDATE_INT) : null;

            try {
                if ($action_id !== null) { // Dit is een UPDATE-actie (ID is aanwezig)
                    $stmt = $conn->prepare("UPDATE temperatures SET sensor_name = :sensor_name, temperature = :temperature WHERE id = :id");
                    $stmt->bindParam(':sensor_name', $input_sensor_name);
                    $stmt->bindParam(':temperature', $input_temperature);
                    $stmt->bindParam(':id', $action_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $message = "<p style='color: green;'>Record met ID " . htmlspecialchars($action_id) . " succesvol bijgewerkt.</p>";
                    // Na een succesvolle update, stuur de gebruiker terug naar de data-overzichtspagina
                    header("Location: view-data.php");
                    exit(); // Belangrijk om de scriptuitvoering te stoppen na een header redirect
                } else { // Dit is een CREATE-actie (geen ID aanwezig, nieuw record)
                    $stmt = $conn->prepare("INSERT INTO temperatures (sensor_name, temperature) VALUES (:sensor_name, :temperature)");
                    $stmt->bindParam(':sensor_name', $input_sensor_name);
                    $stmt->bindParam(':temperature', $input_temperature);
                    $stmt->execute();
                    $message = "<p style='color: green;'>Nieuw record succesvol toegevoegd (ID: " . $conn->lastInsertId() . ").</p>";
                    // Leeg de formuliervelden na succesvolle toevoeging
                    $sensor_name = '';
                    $temperature = '';
                }
            } catch(PDOException $e) {
                $message = "<p style='color: red;'>Fout bij opslaan record: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }
    ?>

    <?php echo $message; ?> <form method="post" action="manage_entry.php">
        <?php if ($id !== null): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <?php endif; ?>

        <label for="sensor_name">Sensor Naam:</label><br>
        <input type="text" id="sensor_name" name="sensor_name" value="<?php echo $sensor_name; ?>" required><br><br>

        <label for="temperature">Temperatuur (°C):</label><br>
        <input type="number" step="0.1" id="temperature" name="temperature" value="<?php echo $temperature; ?>" required><br><br>

        <button type="submit"><?php echo ($id !== null) ? 'Gegevens Bijwerken' : 'Data Toevoegen'; ?></button>
    </form>

    <p><a href="view-data.php">Terug naar Data Overzicht</a></p>
</body>
</html>
