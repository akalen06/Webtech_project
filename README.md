# Temperatuur Monitoring Webapplicatie

## Project Overzicht

Dit project betreft de ontwikkeling en implementatie van een full-stack webapplicatie voor het monitoren van temperatuurdata. Het dient als een demonstratie van vaardigheden in webontwikkeling, serveradministratie, databasebeheer en versiebeheer, conform de eisen van het Web Technologies vak.

NOTE: Dit project rekent op de steun van de personal AI 'Gemini', alle scripts zijn dan ook geschreven door Gemini onderbouwd door mijn kennis, bij deze geef ik dus credit.

De applicatie draait op een Docker-gebaseerde omgeving en maakt gebruik van PHP voor server-side logica, MariaDB als database, en een frontend gebouwd met HTML, CSS en JavaScript voor dataweergave en interactie.

## Kenmerken

* **HTTP Web Server Setup:** Webserver geconfigureerd met Caddy voor HTTP-verkeer op poort 80.
* **Dynamische Content met PHP:** Server-side scripts in PHP voor database-interactie en het genereren van dynamische webpagina's.
* **Frontend Ontwikkeling:** Een gebruiksvriendelijke frontend met HTML voor structuur, CSS voor styling, en JavaScript (Chart.js) voor interactieve datavisualisatie.
* **Database Integratie:** Naadloze integratie met een MariaDB-database voor opslag en beheer van temperatuurdata.
* **CRUD Functionaliteit:** Volledige ondersteuning voor Create, Read, Update en Delete (CRUD) operaties via de webinterface.
    * **Data Invoeren:** Voeg nieuwe temperatuurmetingen toe via een webformulier.
    * **Data Overzicht:** Bekijk alle opgeslagen temperatuurdata in een tabel.
    * **Data Visualisatie:** Interactieve lijngrafiek (Chart.js) toont het temperatuurverloop.
    * **Data Bewerken:** Pas bestaande temperatuurrecords aan via een webformulier.
    * **Data Verwijderen:** Verwijder individuele temperatuurrecords direct vanuit het overzicht.
* **Veiligheid:** Implementatie van voorbereide statements (Prepared Statements) met PDO in PHP om SQL-injecties te voorkomen en gebruik van `htmlspecialchars()` voor Cross-Site Scripting (XSS) preventie.
* **Versiebeheer:** Gebruik van Git en GitHub voor versiebeheer en projectprogressie.

## Gebruikte Technologieën

* **Docker & Docker Compose:** Voor het containeriseren van de applicatiecomponenten en het beheren van de multi-container omgeving.
* **Caddy:** Een moderne, krachtige webserver die HTTP-verzoeken afhandelt.
* **PHP-FPM (8.2):** FastCGI Process Manager voor PHP, die dynamische content genereert.
* **MariaDB:** Een robuuste relationele database voor het opslaan van temperatuurdata.
* **phpMyAdmin:** Een webgebaseerde tool voor het grafisch beheren van de MariaDB-database.
* **HTML5:** Voor de structuur van de webpagina's.
* **CSS3:** Voor de styling en opmaak van de gebruikersinterface.
* **JavaScript:** Voor interactieve elementen en dataverwerking aan de client-side.
* **Chart.js:** Een populaire JavaScript-bibliotheek voor het maken van responsieve en interactieve grafieken.
* **Git & GitHub:** Voor versiebeheer, samenwerking en projectdocumentatie.

## Installatie en Setup

Volg deze stappen om het project lokaal op te zetten en uit te voeren:

### Vereisten

* [Docker Desktop](https://www.docker.com/products/docker-desktop/) geïnstalleerd (inclusief Docker Compose).
* Een Linux-omgeving (bijv. [WSL 2 op Windows](https://learn.microsoft.com/en-us/windows/wsl/install) of een native Linux-distributie).

### Stappen

1.  **Kloon de repository:**
    ```bash
    git clone [https://github.com/](https://github.com/)<akalen06>/Webtech_project.git
    cd Webtech_project
	```

2.  **Start de Docker-containers:**
    Navigeer naar de root van het gekloonde project (`Webtech_project` map, waar `docker-compose.yml` staat) en voer uit:
    ```bash
    docker-compose up -d
    ```
    Dit start Caddy (webserver), PHP-FPM, MariaDB en phpMyAdmin in de achtergrond.

3.  **Database Initialiseren:**
    Ga naar de volgende URL in je webbrowser om de `temperatures` tabel aan te maken:
    ```
    http://localhost/setup_database.php
    ```
    Je zou een succesbericht moeten zien.

4.  **Testdata Invoeren (Optioneel):**
    Om snel wat data in je database te krijgen, bezoek je:
    ```
    http://localhost/insert_data.php
    ```
    Dit voegt enkele voorbeeldtemperatuurmetingen toe.

## Gebruik van de Applicatie

Zodra de containers draaien en de database is ingesteld, kun je de applicatie gebruiken:

* **Hoofdpagina (Statisch):**
    ```
    http://localhost/
    ```
    Hier vind je algemene informatie over het project en navigatielinks.

* **Data Overzicht & Visualisatie (Dynamisch):**
    ```
    http://localhost/view-data.php
    ```
    Toont alle opgeslagen temperatuurdata in een tabel en visualiseert deze in een interactieve lijngrafiek. Vanuit hier kun je records 'Verwijderen' of 'Bewerken'.

* **Data Toevoegen/Bewerken (Dynamisch):**
    ```
    http://localhost/manage_entry.php
    ```
    Dit formulier stelt je in staat om nieuwe temperatuurmetingen toe te voegen. Als je deze pagina benadert via de 'Bewerk'-knop op `view-data.php` (bijv. `http://localhost/manage_entry.php?id=X`), wordt het formulier voorgevuld voor het bewerken van een bestaand record.

* **phpMyAdmin (Database Beheerder):**
    ```
    http://localhost:8080
    ```
    Gebruik deze interface voor direct databasebeheer, inspectie en debugging.
    * **Gebruikersnaam:** `root`
    * **Wachtwoord:** `wachtwoord123` *(Let op: Voor een productieomgeving is het aan te raden sterkere, niet-standaard wachtwoorden en specifieke gebruikers met beperkte rechten te gebruiken.)*

## Project Structuur


Voor vragen of feedback over dit project, contact:
Lennert Engelen
lennert.engelen@Student.pxl.be
