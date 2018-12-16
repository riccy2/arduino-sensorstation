
bool processStarter() {
  if (rtcConnected) {
    // Nutze RTC um Prozess nur Intervallweise anzustoßen und delay() zu vermeiden.
    DateTime processNow = rtc.now();
    if ((processNow.unixtime() - lastProcess) > (intervall * 60)) {
      lastProcess = processNow.unixtime();
      return true;
    } else {
      return false;
    }
  } else {
    unsigned long processNow = millis();
    if ((processNow - lastProcess) > (intervall * 60 * 1000)) {
      lastProcess = processNow;
      return true;
    } else {
      return false;
    }
  }
}
