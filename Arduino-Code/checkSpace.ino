bool checkSpace() {
  FSInfo fs_info;
  SPIFFS.info(fs_info);
  Serial.print("Total Space: ");
  Serial.println(fs_info.totalBytes);
  Serial.print("Used Space: ");
  Serial.println(fs_info.usedBytes);
  
  if (fs_info.usedBytes > fs_info.totalBytes * 0.47) { // nur 47% des Platzes nutzen - falls Datei komplett nicht versendet werden kann, wird sie dubliziert
    return false;
  } else {
    return true;
  }
  
}
