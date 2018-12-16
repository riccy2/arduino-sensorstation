# Arduino Sensorstation

Hochpräzise Vermessungsarbeiten erfordern oft eine genaue Kenntnis des Mediums Luft. Temperatur, Luftdruck und relative Luftfeuchte beeinflussen die benötige Laufzeit von Wellen, die für Streckenmessungen genutzt werden. Das Ziel dieser Arbeit ist, eine kostengünstige Lösung zu entwickeln, die es ermöglicht, flächendeckend Korrekturwerte für hochpräzise Messungen zu liefern. Dazu wird mithilfe der Hard- und Softwareplattform Arduino ein Prototyp entwickelt, welcher Temperatur, Luftdruck und Luftfeuchte messen und an eine zentrale Stelle via WLAN weiterleiten kann. Dazu wurden verschiedene Sensoren hinsichtlich ihrer Genauigkeit untersucht und weitere für eine ausfallsichere Datenerfassung benötigte Komponenten ausgewählt. Ein Programm für die Steuerung einer Messstation sowie für den zentralen Server wurde entwickelt. Das Ergebnis ist Sensornetzwerk, dessen Knoten einen Materialpreis von etwas über 10 € aufweisen und trotzdem genaue Messwerte erfassen.

High-precision surveying work often requires precise knowledge of the medium air. Temperature, air pressure and relative humidity influence the required running time of waves used for distance measurements. The aim of this work is to develop a cost-effective solution that allows to provide area-wide correction values for high-precision measurements. A prototype will be developed using the Arduino hardware and software platform, which can measure temperature, air pressure and humidity and transmit them to a central location via WLAN. For this purpose, various sensors were examined with regard to their accuracy and further components required for fail-safe data acquisition were selected. A program to control a sensor station as well as one for the central server was developed. The result is a sensor network, whose nodes have a material price of just over 10 € and still record accurate measured values.

## Inhalt dieses Repository

### Arduino Code
Dieser Ordner enthält den Quellcode für eine Sensorstation. **SensorStation.ino** ist das Main Sketch File.

### Layout
Dieser Ordner enthält ein Bild des mit fritzing erstellten Layouts einer Sensorstaton. Ebenfalls findet sich dort ein Bild einer Station auf einer Platine.

### PCB-Design
In diesem Ordner ist das entworfene Board in Form einer Eagle-CAD-Boarddatei sowie einer fertigen Gerber-Datei.

### Server-Code
In diesem Ordner befindet sich der Code des PHP-Scripts zum Speichern der Messwerte (**send_data.php**) sowie eines zum Abrufen von Daten (**get_data.php**). Dieses Script zum Abrufen wird von der Testapplikation **index.php** genutzt. Die Testaplikation kann die Daten verschiedener Stationen in einer mit AmCharts erstellten Grafik darstellen.
Die Datei **weather.sql** enthält die Struktur der MySQL-Datenbank.
