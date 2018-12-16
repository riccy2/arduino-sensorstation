  
  void infoDisplay(String line1, String line2, String line3, String line4) {
    if (displayConnected) {
      u8g2.clearBuffer();
      u8g2.setFont(u8g2_font_7x14B_tf );
      u8g2.drawStr(0,16,line1.c_str());
      u8g2.drawStr(0,30,line2.c_str());
      u8g2.drawStr(0,44,line3.c_str()); 
      u8g2.drawStr(0,58,line4.c_str());  
      u8g2.sendBuffer();
    }
  }
