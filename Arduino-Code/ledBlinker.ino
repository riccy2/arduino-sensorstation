// LED Blink
// COMMON CATHODE
void ledBlinker() {
  if (LEDBLINK) {
    const unsigned long onZeit  =  400; // ms
    const unsigned long offZeit = 400; // ms
    if ((millis() % (onZeit + offZeit)) < onZeit) {
      analogWrite(REDPIN, GREENCOLOR);
      analogWrite(GREENPIN, REDCOLOR);
      analogWrite(BLUEPIN, BLUECOLOR);
    } else {
      analogWrite(REDPIN, 0);
      analogWrite(GREENPIN, 0);
      analogWrite(BLUEPIN, 0);
    }
  } else {
    analogWrite(REDPIN, GREENCOLOR);
    analogWrite(GREENPIN, REDCOLOR);
    analogWrite(BLUEPIN, BLUECOLOR);
  }
}
