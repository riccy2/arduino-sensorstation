
String sendRequest(String postStr) {
  if (client.connect(url, 443)) {
    client.println("POST /api/send_data.php HTTP/1.1");
    client.println("Host: " + String(url));
    client.println("User-Agent: ESP8266/1.0");
    client.println("Connection: close");
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.print("Content-Length: ");
    client.println(postStr.length());
    client.println();
    client.print(postStr);

    String response = "";
    while (client.connected()) {
      if (client.available()) {
        char c = client.read();
        response += c;
      }
    }

    Serial.println(response);
    Serial.println("---");
    String responseBody = response.substring(response.indexOf("\r\n\r\n") + 4, response.length());
    client.stop();
    return responseBody;
  } else {
    return "{'status': 'error_client'}";
  }
}
