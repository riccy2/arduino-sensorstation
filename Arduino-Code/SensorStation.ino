/*
   ** SensorStation **
   Programm zur Erfassung und Übermittlung von Wetterdaten
   Autor: Riccardo Oppermann
   Datum: November + Dezember 2018
   Verwendete Komponenten: NodeMCU v2 Amica, Bosch BME280 Modul, DS3231 RTC Modul, 1.3" I2C OLED Display, 

   NodeMCU I2C
   SDA -> D2 (GPIO 4) Standard
   SCL -> D1 (GPIO 5) Standard
   Falls andere Pins genutzt werden sollen, diese festlegen mit Wire.begin(SDApin,SCLpin);

   LED
   Rot  -> D3 (GPIO 0)
   Grün -> D4 (GPIO 2)
   Blau -> D8 (GPIO 15)

   Button
   Signal -> D5 (GPIO 14)
   

*/

#include <FS.h>                   //muss an erster Stelle stehen

#include <Adafruit_Sensor.h>      //https://github.com/adafruit/Adafruit_Sensor
#include <Adafruit_BME280.h>      //https://github.com/adafruit/Adafruit_BME280_Library change I2C-Adress to 0x76
#include <RTClib.h>               //https://github.com/adafruit/RTClib
#include <U8g2lib.h>              //https://github.com/olikraus/u8g2
#include <U8x8lib.h>              //https://github.com/olikraus/u8g2
#include <Wire.h>                 //https://www.arduino.cc/en/Reference/Wire
#include <SPI.h>                  //https://www.arduino.cc/en/Reference/SPI
#include <ESP8266WiFi.h>          //https://github.com/esp8266/Arduino
#include <DNSServer.h>            //https://github.com/esp8266/Arduino
#include <ESP8266WebServer.h>     //https://github.com/esp8266/Arduino
#include <WiFiClientSecure.h>     //https://github.com/esp8266/Arduino
#include <WiFiManager.h>          //https://github.com/tzapu/WiFiManager
#include <ArduinoJson.h>          //https://github.com/bblanchon/ArduinoJson Version 5.X.X


WiFiClientSecure client;
WiFiManager wifiManager;
File logDatei;
String line;
Adafruit_BME280 bme280;
RTC_DS3231 rtc;
U8G2_SH1106_128X64_NONAME_F_HW_I2C u8g2(U8G2_R0, /* reset=*/ U8X8_PIN_NONE);


int SDAPIN = 4; // D2
int SCLPIN = 5; // D1
int REDPIN = 0; // D3
int GREENPIN = 2; // D4
int BLUEPIN = 15; // D8
int buttonPin = 14; // D5



// Einstellungen
bool buttonConnected = true;  // auf false setzen, falls kein Button angeschlossen wurde
const char* url = "www.weatherweb.de"; // Serveradresse der API, erwartet folgende Dateistruktur: "/api/send_data.php"



// Globale Statusvariablen, werden vom Programm gesetzt
float bme280Temp;
float bme280Pres;
float bme280Humi;
const char* stationname;
String message = "";
char web_token[34] = "";
int intervall = 1;
bool showWifiPortal = true;
bool shouldSaveConfig = false;
bool spaceAvailable = false;
bool displayConnected = false;
bool rtcConnected = false;
bool bme280Connected = false;
bool httpFail = false;
unsigned long lastProcess = 0;
unsigned long lastDisplayRefresh = 0;
bool LEDBLINK = false;
int REDCOLOR = 0;
int GREENCOLOR = 0;
int BLUECOLOR = 0;




void setColor(int red, int green, int blue, bool blinkMode = false);

// wird aufgerufen, wenn die Wifi-Konfiguration gespeichert werden soll
void saveConfigCallback () {
  Serial.println("Konfiguration speichern");
  shouldSaveConfig = true;
}


void setup() {
  pinMode(REDPIN, OUTPUT);
  pinMode(GREENPIN, OUTPUT);
  pinMode(BLUEPIN, OUTPUT);
  pinMode(buttonPin, INPUT);
  
  Serial.begin(9600);
  Wire.begin(SDAPIN, SCLPIN);
  delay(1000);

  Wire.beginTransmission(104); // entspricht 0x68 (DS3231 Adresse)
  byte errorRTC = Wire.endTransmission();
  if (errorRTC == 0) {
    Serial.println("I2C RTC gefunden");
    rtcConnected = true;
  }
  if (rtcConnected) {
    rtc.begin();
    if (rtc.lostPower()) {
      Serial.println("RTC Zeit wird gesetzt");
      // RTC Zeit auf den Zeitpunkt der Kompilierung setzen
      rtc.adjust(DateTime(F(__DATE__), F(__TIME__)));
    }
  }

  Wire.beginTransmission(60); // entspricht 0x3C (Display Adresse)
  byte errorDisplay = Wire.endTransmission();
  if (errorDisplay == 0) {
    Serial.println("I2C Display gefunden");
    displayConnected = true;
  }
  if (displayConnected) {
    u8g2.begin();
    u8g2.enableUTF8Print();
  }

  infoDisplay("", "Bitte warten", "Starte Station...", "");
  delay(500);

  Wire.beginTransmission(118); // entspricht 0x76 (BME280 Adresse)
  byte errorBME280 = Wire.endTransmission();
  if (errorRTC == 0) {
    Serial.println("I2C BME280 gefunden");
    bme280Connected = true;
  }
  if (bme280Connected) {
    bme280.begin();
  } else {
    Serial.println("Konnte keinen BME280 Sensor finden, Programm wird abgebrochen.");
    infoDisplay("", "Fehler", "Kein BME280 gefunden", "");
    while (1) {
      analogWrite(REDPIN, 255);
    };
  }

  

  //clean FS, for testing
  //SPIFFS.format();

  //read configuration from FS json
  Serial.println("mounting FS...");

  if (SPIFFS.begin()) {
    Serial.println("mounted file system");
    if (SPIFFS.exists("/config.json")) {
      //file exists, reading and loading
      Serial.println("reading config file");
      File configFile = SPIFFS.open("/config.json", "r");
      if (configFile) {
        Serial.println("opened config file");
        size_t size = configFile.size();
        // Allocate a buffer to store contents of the file.
        std::unique_ptr<char[]> buf(new char[size]);

        configFile.readBytes(buf.get(), size);
        DynamicJsonBuffer jsonBuffer;
        JsonObject& json = jsonBuffer.parseObject(buf.get());
        json.printTo(Serial);
        if (json.success()) {
          Serial.println("\nparsed json");

          strcpy(web_token, json["web_token"]);
          // already configured
          showWifiPortal = false;

        } else {
          Serial.println("failed to load json config");
        }
        configFile.close();
      }
    }
  } else {
    Serial.println("failed to mount FS");
  }
  //end read






  if (showWifiPortal) {
    infoDisplay("", "Wlan einrichten", "", "");


    // The extra parameters to be configured (can be either global or just in the setup)
    // After connecting, parameter.getValue() will get you the configured value
    // id/name placeholder/prompt default length
    WiFiManagerParameter par_web_token("token", "Token", web_token, 32);
  
    //set config save notify callback
    wifiManager.setSaveConfigCallback(saveConfigCallback);
  
    //add all your parameters here
    wifiManager.addParameter(&par_web_token);
  
    //reset settings - for testing
    // wifiManager.resetSettings();

    if (!wifiManager.startConfigPortal()) {
      Serial.println("failed to connect and hit timeout");
      delay(3000);
      //reset and try again, or maybe put it to deep sleep
      ESP.reset();
      delay(5000);
    }

    //if you get here you have connected to the WiFi
    Serial.println("connected to wifi");
    infoDisplay("verbunden mit", String(WiFi.SSID()), "", WiFi.localIP().toString());
    delay(1000);
  
    //read updated parameters
    strcpy(web_token, par_web_token.getValue());
    
  } else{
    wifiManager.autoConnect();
  }

  

  //fetches ssid and pass and tries to connect
  //if it does not connect it starts an access point
  //and goes into a blocking loop awaiting configuration
//  if (!wifiManager.autoConnect()) {
//    Serial.println("failed to connect and hit timeout");
//    delay(3000);
//    //reset and try again, or maybe put it to deep sleep
//    ESP.reset();
//    delay(5000);
//  }



  //save the custom parameters to FS
  if (shouldSaveConfig) {
    Serial.println("saving config");
    DynamicJsonBuffer jsonBuffer;
    JsonObject& json = jsonBuffer.createObject();
    json["web_token"] = web_token;

    File configFile = SPIFFS.open("/config.json", "w");
    if (!configFile) {
      Serial.println("failed to open config file for writing");
    }

    json.printTo(Serial);
    json.printTo(configFile);
    configFile.close();
    //end save
  }

  Serial.println("local ip");
  Serial.println(WiFi.localIP());

}

void loop() {

  if (buttonConnected) {
    int buttonState = 0;
    buttonState = digitalRead(buttonPin);
    if (buttonState == HIGH) {
      Serial.println("button pressed");
      delay(200);
      SPIFFS.remove("/config.json");
      delay(200);
      wifiManager.resetSettings();
      delay(1000);
      ESP.restart();
    }
  }
  
  ledBlinker();
  
  if (processStarter()) {

    httpFail = false;

    spaceAvailable = checkSpace();

    bme280Temp = bme280.readTemperature();
    bme280Pres = bme280.readPressure() / 100;
    bme280Humi = bme280.readHumidity();

    String postStr = "action=update";
    postStr += "&temp=" + String(bme280Temp);
    postStr += "&pres=" + String(bme280Pres);
    postStr += "&humi=" + String(bme280Humi);
    postStr += "&token=" + String(web_token);
    String responseBody = sendRequest(postStr);


    StaticJsonBuffer<200> jsonBuffer;
    JsonObject& body = jsonBuffer.parseObject(responseBody);

    const char* requestStatus;
    if (body.containsKey("status")) {            // Erhalte Request-Status vom Server
      requestStatus = body["status"];
    }

    if (body.containsKey("stationname")) {       // Erhalte Stationsname vom Server
      stationname = body["stationname"];
    }

    if (body.containsKey("intervall")) {          // Erhalte Intervall in Minuten vom Server
      intervall = body["intervall"];
    }

    unsigned int timestamp;
    if (body.containsKey("timestamp")) {        // Erhalte Unix-Timestamp vom Server, so kann die RTC aktuell gehalten werden, solange Internet da ist.
      timestamp = body["timestamp"];     
      if (rtcConnected) {
        rtc.adjust(DateTime(timestamp));
      }
      timestamp = timestamp + 7200;   // Wegen Zeitzone + 2 Stunden - erst nachdem RTC gestellt wurde, da unixzeit zeitzonenunabhängig ist
    }

    

    if (strcmp(requestStatus, "success") == 0) {
      Serial.println("Real Time Request successful");
      String minutes, hours;
      if (DateTime(timestamp).minute() < 10) {
        minutes = "0" + String(DateTime(timestamp).minute());
      } else {
        minutes = String(DateTime(timestamp).minute());
      }
      if (DateTime(timestamp).hour() < 10) {
        hours = "0" + String(DateTime(timestamp).hour());
      } else {
        hours = String(DateTime(timestamp).hour());
      }
      message = "zuletzt " + hours + ':' + minutes;
      setColor(0, 255, 0);
    } else if (strcmp(requestStatus, "error_mysql") == 0) {
      setColor(0, 0, 255, true);
      Serial.println("error_mysql");
      message = "SQL Fehler";
      httpFail = true;
    } else if (strcmp(requestStatus, "error_token") == 0) {
      Serial.println("error_token");
      wifiManager.resetSettings();
      delay(1000);
      ESP.restart();
    } else if (strcmp(requestStatus, "error_client") == 0) {
      Serial.println("error_client");
      message = "Wifi Fehler";
      setColor(255, 140, 0, true);
      httpFail = true;
    } else {
      // unbekannter Fehler
      Serial.println("Real Time Request failed");
      message = "unbekannter Fehler"; 
      httpFail = true;
    }

    refreshDisplay(String(stationname), bme280Temp, bme280Pres, bme280Humi, message);

    if (httpFail) { 
      // HTTP-Request konnte nicht abgesetzt werden
      writeLog();
    } else {  
      // voheriger Request konnte abgesetzt werden, Connection ist also vorhanden -> Cache checken und abarbeiten
      sendLog();
    }

    Serial.println("---------------------------------------");
  } // end Process

}


String getTimeString(DateTime now) {
  String timeString;

  timeString = String(now.year()) + '-' + String(now.month()) + '-' + String(now.day());
  timeString = timeString + ' ';
  timeString = timeString + String(now.hour()) + ':' + String(now.minute()) + ':' + String(now.second());
  return timeString;
}

void setColor(int red, int green, int blue, bool blinkMode) {
  REDCOLOR = red;
  GREENCOLOR = green;
  BLUECOLOR = blue;
  LEDBLINK = blinkMode;
}
