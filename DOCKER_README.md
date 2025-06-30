# Docker-Setup für Web4 Lite

Dieses Dokument beschreibt, wie man dieses Projekt mit Docker und Docker Compose einrichtet und ausführt.

## Was sind Docker und Docker-Container?

**Docker** ist eine Open-Source-Plattform, die es Entwicklern ermöglicht, Anwendungen in sogenannten **Containern** zu erstellen, bereitzustellen und auszuführen.

Ein **Container** ist eine leichtgewichtige, eigenständige, ausführbare Einheit, die alles enthält, was zum Ausführen einer Anwendung erforderlich ist: Code, Laufzeitumgebung, Systemwerkzeuge, Systembibliotheken und Einstellungen. Container isolieren Anwendungen voneinander und von der zugrunde liegenden Infrastruktur, was zu Konsistenz über verschiedene Umgebungen hinweg führt (Entwicklung, Test, Produktion).

**Docker Compose** ist ein Werkzeug zum Definieren und Ausführen von Multi-Container-Docker-Anwendungen. Mit einer YAML-Datei (`docker-compose.yml`) können Sie die Dienste, Netzwerke und Volumes Ihrer Anwendung konfigurieren und dann mit einem einzigen Befehl alle Dienste erstellen und starten.

## Vorteile der Verwendung von Docker für dieses Projekt

- **Konsistente Umgebung**: Stellt sicher, dass die Anwendung in der Entwicklung, im Test und in der Produktion in derselben Umgebung läuft und das "Es funktioniert auf meinem Rechner"-Problem reduziert.
- **Einfache Einrichtung**: Neue Entwickler können das Projekt schnell zum Laufen bringen, ohne manuell Abhängigkeiten wie PHP, Apache und MySQL installieren und konfigurieren zu müssen.
- **Isolation**: Die Projektumgebung ist von der lokalen Maschine isoliert, wodurch Konflikte mit anderen Projekten oder global installierter Software vermieden werden.
- **Portabilität**: Die containerisierte Anwendung kann leicht auf jedem System ausgeführt werden, auf dem Docker installiert ist.
- **Skalierbarkeit**: Docker-Container können bei Bedarf leicht skaliert werden (obwohl dies für dieses spezifische Setup möglicherweise zusätzliche Konfiguration erfordert).

## Voraussetzungen

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (für Windows, macOS oder Linux) muss installiert sein. Dies beinhaltet Docker Engine und Docker CLI.

## Einrichten und Starten der Anwendung

1.  **Klone das Repository** (falls noch nicht geschehen):
    ```bash
    git clone <repository-url>
    cd <repository-directory>
    ```

2.  **Datenbankkonfiguration anpassen**:
    Öffne die Datei `sys/config/db.inc.php`. Du musst die folgenden Zeilen ändern, um die in `docker-compose.yml` definierten Datenbankdetails zu verwenden:

    ```php
    // ... andere Konfigurationen ...

    //Please edit database data

    $C['DB_HOST'] = "db";                                         // Docker-Servicename für MySQL
    $C['DB_USER'] = "app_user";                                   // Entspricht MYSQL_USER in docker-compose.yml
    $C['DB_PASS'] = "app_password";                               // Entspricht MYSQL_PASSWORD in docker-compose.yml
    $C['DB_NAME'] = "app_db";                                     // Entspricht MYSQL_DATABASE in docker-compose.yml

    // ... restliche Konfigurationen ...
    ```
    **Hinweis**: Die Werte für `DB_USER`, `DB_PASS` und `DB_NAME` müssen mit den Werten für `MYSQL_USER`, `MYSQL_PASSWORD` und `MYSQL_DATABASE` übereinstimmen, die im `db`-Dienst in der `docker-compose.yml`-Datei festgelegt sind. `DB_HOST` sollte auf `db` gesetzt werden, da dies der Name des MySQL-Dienstes innerhalb des Docker-Netzwerks ist.

3.  **Starte die Anwendung mit Docker Compose**:
    Führe im Stammverzeichnis des Projekts (wo sich `docker-compose.yml` befindet) den folgenden Befehl aus:
    ```bash
    docker-compose up -d
    ```
    -   `up`: Erstellt und startet die Container.
    -   `-d`: Führt die Container im Hintergrund aus (detached mode).

    Beim ersten Ausführen dieses Befehls lädt Docker die notwendigen Images (PHP, MySQL) herunter und baut das Anwendungsimage. Dies kann einige Minuten dauern. Nachfolgende Starts sind schneller.

4.  **Greife auf die Anwendung zu**:
    Sobald die Container laufen, ist die Webanwendung unter [http://localhost:8080](http://localhost:8080) in deinem Webbrowser erreichbar.

5.  **Datenbankimport (falls erforderlich)**:
    Wenn du eine vorhandene Datenbank-Dump-Datei (`.sql`) hast, die du importieren möchtest:
    a.  Finde die Container-ID des MySQL-Containers: `docker ps`
    b.  Kopiere die SQL-Datei in den MySQL-Container: `docker cp deine_dump_datei.sql <mysql_container_id>:/tmp/deine_dump_datei.sql`
    c.  Greife auf die Shell des MySQL-Containers zu: `docker exec -it <mysql_container_id> bash`
    d.  Importiere die Datenbank: `mysql -u root -p<MYSQL_ROOT_PASSWORD> <MYSQL_DATABASE> < /tmp/deine_dump_datei.sql` (Ersetze `<MYSQL_ROOT_PASSWORD>` und `<MYSQL_DATABASE>` mit den Werten aus `docker-compose.yml`). Beachte, dass zwischen `-p` und dem Passwort kein Leerzeichen steht.
    e.  Verlasse die Shell des Containers: `exit`

    Für eine Neuinstallation musst du möglicherweise den Installationsprozess der Anwendung durchlaufen, falls vorhanden, oder die erforderlichen Tabellen manuell erstellen/importieren. Die Anwendung erwartet eine Datenbankstruktur, die sie verwenden kann.

## Nützliche Docker-Befehle

-   **Container stoppen**:
    ```bash
    docker-compose down
    ```
    Dies stoppt und entfernt die Container, aber das Datenbankvolume (`db_data`) bleibt erhalten, sodass deine Daten beim nächsten Start wieder verfügbar sind.

-   **Container stoppen und Volumes entfernen** (Vorsicht: Dies löscht die Datenbankdaten):
    ```bash
    docker-compose down -v
    ```

-   **Status der laufenden Container anzeigen**:
    ```bash
    docker-compose ps
    ```
    Oder für alle Docker-Container:
    ```bash
    docker ps -a
    ```

-   **Logs der Container anzeigen**:
    ```bash
    docker-compose logs -f <service_name>
    ```
    (z.B. `docker-compose logs -f web` oder `docker-compose logs -f db`)
    Das `-f` steht für "follow" und streamt die Logs live.

-   **Einen Befehl in einem laufenden Container ausführen**:
    ```bash
    docker-compose exec <service_name> <befehl>
    ```
    (z.B. um eine Bash-Shell im Web-Container zu öffnen: `docker-compose exec web bash`)

-   **Das Anwendungsimage neu bauen** (wenn du Änderungen am Dockerfile vorgenommen hast):
    ```bash
    docker-compose build
    ```
    Oder um ein bestimmtes Service-Image neu zu bauen:
    ```bash
    docker-compose build web
    ```
    Oft ist es am besten, `docker-compose up -d --build` zu verwenden, um neu zu bauen und dann neu zu starten.

## Fehlerbehebung

-   **Port-Konflikte**: Wenn Port `8080` oder `3306` auf deinem Host-System bereits verwendet wird, ändere die Port-Mappings in `docker-compose.yml` (z.B. `"8081:80"` für die Web-App).
-   **Berechtigungsprobleme**: Wenn die Anwendung Probleme beim Schreiben von Dateien hat (z.B. Uploads, Cache), überprüfe die Dateiberechtigungen im Container. Die Zeile `RUN chown -R www-data:www-data /var/www/html` im Dockerfile sollte die meisten dieser Probleme beheben.
-   **Datenbankverbindungsprobleme**:
    -   Stelle sicher, dass der `db`-Container läuft (`docker-compose ps`).
    -   Überprüfe die Logs des `db`-Containers (`docker-compose logs db`) auf Fehler.
    -   Vergewissere dich, dass die Anmeldeinformationen in `sys/config/db.inc.php` exakt mit denen in `docker-compose.yml` für den `db`-Dienst übereinstimmen und dass `DB_HOST` auf `db` gesetzt ist.
-   **Composer-Probleme**: Wenn es Probleme während des `composer install`-Schritts gibt, überprüfe die Logs des Build-Prozesses: `docker-compose build --no-cache web`. Manchmal können Netzwerkprobleme oder fehlende PHP-Erweiterungen die Ursache sein. Das aktuelle Dockerfile versucht, die gängigsten Erweiterungen zu installieren.

Bei weiteren Problemen können die Logs der jeweiligen Container (`docker-compose logs web` oder `docker-compose logs db`) oft Aufschluss über die Ursache geben.
