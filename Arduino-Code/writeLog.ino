void writeLog() {
  if (rtcConnected && spaceAvailable) { // Daten nur in Datei schreiben, wenn eine RTC angeschlossen ist und noch Platz ist
    DateTime now = rtc.now();
    logDatei = SPIFFS.open("/LOG.TXT", "w");   // Daten in Datei auf SPIFFS schreiben
    if (logDatei) {
      logDatei.print(now.unixtime());
      logDatei.print(";");
      logDatei.print(String(bme280Temp));
      logDatei.print(";");
      logDatei.print(String(bme280Pres));
      logDatei.print(";");
      logDatei.print(String(bme280Humi));
      logDatei.println();
      logDatei.close();
    } else {  // Daten konnten nicht in Datei geschrieben werden.
      setColor(255, 0, 0, true);
      Serial.println("Fehler beim Öffnen der Datei.");
    }
  } else {  // Keine RTC verbunden
    setColor(255, 0, 0, true);
    Serial.println("Keine RTC verbunden.");
  }
}
