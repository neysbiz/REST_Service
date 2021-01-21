# PDF_Push
Push Docby MQTT

- LAMP Server benötigt (MariaDB statt MySQL verwendet)
- diverse ApacheModule installieren

Damit die .htaccess Datei auf Verzeichnisebene berücksichtigt wird:
  sudo a2enmod rewrite
  sudo editor /etc/apache2/sites-available/000-default.conf
          <Directory /var/www/html/show_ext>
            AllowOverride All
          </Directory>
- Die .htaccess Datei des API Ordners anpassen
    Damit ein anderes URL Format verwendet werden kann: statt .../api/api.php?<Dateiname> => .../api/api/<Dateiname>
- JWT installieren
    .htaccess Datei anpassen, damit der JW-Token im Header übergeben und verwendet werden kann
- Mosquitto installieren
    mosquitto.conf anpassen und Websocket auf 9001 aktivieren
