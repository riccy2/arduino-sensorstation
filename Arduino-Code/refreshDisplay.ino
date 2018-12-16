  
  void refreshDisplay(String stationname, float temp, float pres, float humid, String message) {
    if (displayConnected) {
      String cTemp = String(temp) + " C";
      String cPres = String(pres) + " hPa";
      String cHumi = String(humid) + " %";
      
      u8g2.clearBuffer();
      u8g2.setFont(u8g2_font_7x14B_tf);
      u8g2.drawStr(0,12,stationname.c_str()); 
      u8g2.setFont(u8g2_font_helvR08_tf);
      u8g2.drawStr(0,26,"Temperatur:");
      u8g2.drawStr(64,26,cTemp.c_str());
      u8g2.drawStr(0,38,"Luftdruck:");
      u8g2.drawStr(64,38,cPres.c_str());
      u8g2.drawStr(0,50,"Luftfeuchte:");
      u8g2.drawStr(64,50,cHumi.c_str());
      u8g2.drawStr(0,62,message.c_str());
      u8g2.sendBuffer();
    }
  }
