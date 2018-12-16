void sendLog() {
  if (rtcConnected) {
    logDatei = SPIFFS.open("/LOG.TXT", "r");
    if (!logDatei) {
      Serial.println("Keine Cache Datei gefunden.");
    } else {
      Serial.println("Cache Datei gefunden, beginne mit lesen.");
      while (logDatei.available()) {
        line = logDatei.readStringUntil('\n');
        Serial.println(line);
  
        String postStr = "action=send_cache";
        postStr += "&line=" + line;
        postStr += "&token=" + String(web_token);
  
        String responseBody = sendRequest(postStr);
  
        StaticJsonBuffer<200> jsonBuffer;
        JsonObject& body = jsonBuffer.parseObject(responseBody);
  
        const char* requestStatus;
        if (body.containsKey("status")) {
          requestStatus = body["status"];
        }
  
        if (strcmp(requestStatus, "success") == 0) {      // Alles gut, Line wurde abgeschickt
          Serial.println("Line send");
        } else if (strcmp(requestStatus, "error_mysql") == 0){                                          // Line konnte nicht abgeschickt werden
          Serial.println("error_mysql");
          File cacheNotSend = SPIFFS.open("/LOG_FAIL.TXT", "w");    // Schreibe Line in neue Datei für einen neuen Versuch später mal
          if (cacheNotSend) {
            cacheNotSend.print(line);
            cacheNotSend.println();
            cacheNotSend.close();
          }
        } else {
          Serial.println("error_line");
        }
      }
  
      // Alle Lines abgearbeitet.
      logDatei.close();
      SPIFFS.remove("/LOG.TXT");
      Serial.println("LOG.TXT removed");
  
      if (SPIFFS.exists("/LOG_FAIL.TXT")) {
        SPIFFS.rename("/LOG_FAIL.TXT", "/LOG.TXT");
        Serial.println("LOG_FAIL.TXT renamed");
      }
    }
  }
}
