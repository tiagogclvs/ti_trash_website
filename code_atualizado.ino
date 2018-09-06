  /////////////////////
//       TI-Trash      //
//                     //
//       Autores:      //
//   Tiago Goncalves   //
//  Alexandre Salgado  //
// ------------------- //
//     com ajuda de    //
// Dr. Fernando Moita  //
  /////////////////////

//Testado com Arduino Uno


#include <Wire.h>
#include <SPI.h>
#include <LiquidCrystal_I2C.h> //LCD
#include <MFRC522.h> //RFID
#include <HX711_ADC.h> //HX711 LOAD CELLS
#include <virtuabotixRTC.h>


#define SS_PIN 10
#define RST_PIN 9

//Rele
#define pinR1 5

//BUZZER
int BUZZER = 4;

//Load Cell
HX711_ADC LoadCell(3, 2);
long t;

//RFID
MFRC522 mfrc522(SS_PIN, RST_PIN);

//LCD
LiquidCrystal_I2C lcd(0x27, 2, 1, 0, 4, 5, 6, 7, 3, POSITIVE);

//RTC
virtuabotixRTC myRTC(6, 7, 8);

void setup() {

  //Rele
  pinMode(pinR1, OUTPUT);
  digitalWrite(pinR1, LOW); //SOLENOIDE SEM FORNECIMENTO DE ENERGIA

  Serial.begin(9600);
  SPI.begin();
  lcd.begin(20, 4); //TAMANHO DE LCD; 20 CARACTERES DE COMPRIMENTO POR 4 DE LARGURA
  LoadCell.begin();
  mfrc522.PCD_Init();

  pinMode(BUZZER, OUTPUT);
  noTone(BUZZER);
  lcd.setBacklight(HIGH);

  myRTC.updateTime();

  lcd.print("------TI-Trash------");
  lcd.setCursor(0, 1);      
  lcd.print("   ");
  myRTC.updateTime();
  lcd.print(myRTC.dayofmonth); 
  lcd.print("/");                                                                                                                                                   
  lcd.print(myRTC.month);                                                                             
  lcd.print("/");                                                                                     
  lcd.print(myRTC.year);                                                                              
  lcd.print("  ");                                                                                   
  lcd.print(myRTC.hours);                                                                            
  lcd.print(":");                                                                                     
  lcd.print(myRTC.minutes);                                                                                                                                                            
  lcd.setCursor(0, 4);
  lcd.print("LOADING...");
  delay(2500);
  lcd.setCursor(0, 4);
  lcd.print(">APROXIME SEU CARTAO");
}

void loop() {
     
  if ( ! mfrc522.PICC_IsNewCardPresent()) {
    return;
  }
  if (!mfrc522.PICC_ReadCardSerial()) {
    return;
  }
  String content = "";
  byte letter;
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    Serial.print(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : " ");
    Serial.print(mfrc522.uid.uidByte[i], HEX);
    content.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : " "));
    content.concat(String(mfrc522.uid.uidByte[i], HEX));
  }
  content.toUpperCase();
  if (content.substring(1) == "92 7B BD 30") { //UTILIZADOR: Tiago Gonçalves

    Serial.print("\t");
    Serial.print("1"); // ID DE UTILIZADOR: 1
    Serial.print("\t");
    
    //ID DO CONTAINER: 1
    Serial.print("1");
    Serial.print("\t");

    digitalWrite(pinR1, HIGH); //Solenoide recebe fornecimento de energia

    lcd.clear();
    lcd.setCursor(0, 1);
    lcd.print("  Acesso Concedido  ");
    tone(BUZZER, 200);
    delay(650);
    noTone(BUZZER);
    delay(750);
    lcd.clear();
    lcd.print("Bem vindo");
    lcd.setCursor(0, 1);
    lcd.print("Sr. Tiago Goncalves");
    delay(2500);
    lcd.clear();
    long stabilisingtime = 2000; // a precisao da tare pode ser melhorada com a adicao de alguns milisegundos
    LoadCell.start(stabilisingtime);
    LoadCell.setCalFactor(696.0); // factor de calibracao (float)
    LoadCell.update();
    float i = LoadCell.getData();
    lcd.print(" >INTRODUZA OS SEUS ");
    lcd.setCursor(0, 1);
    lcd.print("      RESIDUOS      ");
    lcd.setCursor(0, 4);
    lcd.print("Peso: ");
    lcd.print(i);
    Serial.print(i);
    lcd.print(" kg");
    Serial.print("\t");   
    myRTC.updateTime();//| 
    Serial.print(myRTC.dayofmonth);                                                                        //| 
    Serial.print("/");                                                                                     //| 
    Serial.print(myRTC.month);                                                                             //| 
    Serial.print("/");                                                                                     //| 
    Serial.print(myRTC.year);                                                                              //| 
    Serial.print("  ");                                                                                    //| 
    Serial.print(myRTC.hours);                                                                             //| 
    Serial.print(":");                                                                                     //| 
    Serial.print(myRTC.minutes);                                                                           //| 
    Serial.println(""); //SEM ESTA LINHA, O CODIGO DA CAPTACAO DE DADOS DO OUTPUT NAO FUNCIONA
    delay(3250);
    digitalWrite(pinR1, LOW);
    lcd.clear();
    lcd.setCursor(0, 1);
    lcd.print("   Peso Registado   ");
    delay(3500);
    lcd.clear();
    lcd.setCursor(0, 1);
    lcd.print(" O planeta agradece ");
    lcd.setCursor(0, 2);
    lcd.print("    a sua ajuda!    ");
    delay(3000);
    lcd.clear();

    lcd.print("------TI-Trash------");
    lcd.setCursor(0, 1); 
    myRTC.updateTime();     
    lcd.print("  ");
    lcd.print(myRTC.dayofmonth); 
    lcd.print("/");                                                                                                                                                   
    lcd.print(myRTC.month);                                                                             
    lcd.print("/");                                                                                     
    lcd.print(myRTC.year);                                                                              
    lcd.print("  ");                                                                                   
    lcd.print(myRTC.hours);                                                                            
    lcd.print(":");                                                                                     
    lcd.print(myRTC.minutes);                                                                                                                                                
    lcd.setCursor(0, 4);
    lcd.print("LOADING...");
    delay(2500);
    lcd.setCursor(0, 4);
    lcd.print(">APROXIME SEU CARTAO");
  }

    else if (content.substring(1) == "F3 36 4B 73") { //ID DE UTILIZADOR: Alexandre Salgado

    Serial.print("\t");
    Serial.print("2"); //ID DE UTILIZADOR: 2
    Serial.print("\t");
    
    //ID DO CONTAINER: 2
    Serial.print("2");
    Serial.print("\t");

    digitalWrite(pinR1, HIGH); //Solenoide recebe fornecimento de energia

    lcd.clear();
    lcd.setCursor(0, 1);
    lcd.print("  Acesso Concedido  ");
    tone(BUZZER, 200);
    delay(650);
    noTone(BUZZER);
    delay(750);
    lcd.clear();
    lcd.print("Bem vindo");
    lcd.setCursor(0, 1);
    lcd.print("Sr. Alexandre Salgad");
    delay(2500);
    lcd.clear();
    long stabilisingtime = 2000; // a precisao da tare pode ser melhorada com a adicao de alguns milisegundos
    LoadCell.start(stabilisingtime);
    LoadCell.setCalFactor(696.0); // factor de calibracao (float)
    LoadCell.update();
    float i = LoadCell.getData();
    lcd.print(" >INTRODUZA OS SEUS ");
    lcd.setCursor(0, 1);
    lcd.print("      RESIDUOS      ");
    lcd.setCursor(0, 4);
    lcd.print("Peso: ");
    lcd.print(i);
    Serial.print(i);
    Serial.print("\t");
    lcd.print(" kg");       
    myRTC.updateTime();
    Serial.print(myRTC.dayofmonth);                                                                        //| 
    Serial.print("/");                                                                                     //| 
    Serial.print(myRTC.month);                                                                             //| 
    Serial.print("/");                                                                                     //| 
    Serial.print(myRTC.year);                                                                              //| 
    Serial.print("  ");                                                                                    //| 
    Serial.print(myRTC.hours);                                                                             //| 
    Serial.print(":");                                                                                     //| 
    Serial.print(myRTC.minutes);                                                                           //| 
    Serial.println(""); //SEM ESTA LINHA, O CODIGO DA CAPTACAO DE DADOS DO OUTPUT NAO FUNCIONA
    delay(3250);
    digitalWrite(pinR1, LOW);
    lcd.clear();
    lcd.setCursor(0, 1);
    lcd.print("   Peso Registado   ");
    delay(3500);
    lcd.clear();
    lcd.setCursor(0, 1);
    lcd.print(" O planeta agradece ");
    lcd.setCursor(0, 2);
    lcd.print("    a sua ajuda!    ");
    delay(3000);
    lcd.clear();

  lcd.print("------TI-Trash------");
  myRTC.updateTime();
  lcd.setCursor(0, 1);      
  lcd.print("  ");
  lcd.print(myRTC.dayofmonth); 
  lcd.print("/");                                                                                                                                                   
  lcd.print(myRTC.month);                                                                             
  lcd.print("/");                                                                                     
  lcd.print(myRTC.year);                                                                              
  lcd.print("  ");                                                                                   
  lcd.print(myRTC.hours);                                                                            
  lcd.print(":");                                                                                     
  lcd.print(myRTC.minutes);                                                                                                                                                  
  lcd.setCursor(0, 4);
  lcd.print("LOADING...");
  delay(2500);
  lcd.setCursor(0, 4);
  lcd.print(">APROXIME SEU CARTAO");
  }
  
  else {
    lcd.clear();
    lcd.setCursor(0, 1);
    lcd.print("   ACESSO NEGADO!   ");
    tone(BUZZER, 300);
    delay(2000);
    noTone(BUZZER);
    delay(7500);
    lcd.clear();
    lcd.setCursor(0, 1);
    lcd.print("       CARTAO       ");
    lcd.setCursor(0,2);
    lcd.print("   >NAO REGISTADO   ");
    delay(3200);
    lcd.clear();
    ///////////RENOVACAO DE CODIGO////////////
    lcd.print("------TI-Trash------");
    lcd.setCursor(0, 1);
    lcd.print("     19/09/2018     ");
    delay(2500);
    lcd.setCursor(0, 4);
    lcd.print("LOADING...");
    delay(2500);
    lcd.setCursor(0, 4);
    lcd.print(">APROXIME SEU CARTAO");
  }
}

