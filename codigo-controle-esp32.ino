//Bibliotecas
#include "DHT.h"
#include <HTTPClient.h>
#include <WiFi.h>
#include <NTPClient.h>
#include <WiFiUdp.h>
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "a.st1.ntp.br", -3 * 3600, 60000);

//configuração de rede
const char* ssid = "TESTE";                       //Nome da rede conectada
const char* password =  "teste2023";        //senha da rede conectada

//Variables used in the code
String LED_id = "1";                  //Just in case you control more than 1 LED
bool toggle_pressed = false;          //Each time we press the push button    
String data_to_send = "";             //Text data to send to the server
unsigned int Actual_Millis, Previous_Millis;
int refresh_time = 500;               //Refresh rate of connection to website (recommended more than 1s)

int ultimaLeitura = 0;  //para verificar a umidade e não mandar dados repitidos para o banco de dados
int penultimaLeitura = 0;

#define pinPOT 32   //potenciomentro simulando sensor DHT
#define pinUmid 26   //led vermelho simulado umidificador
#define pinAr 27   //led azul simulando ar condicionado
#define button1 4        //Connect push button on this pin
#define LED 2           //Connect LED on this pin (add 150ohm resistor)
#define rele1 16       //rele do humidificador
#define rele2 15       //rele do ar (lâmpada)

#define DHTPIN 5
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

float u;
float t;

//Button press interruption
void IRAM_ATTR isr() {
  toggle_pressed = true; 
}

void setup() {
  delay(10);
  Serial.begin(115200);                   //Start monitor

  dht.begin();

  pinMode(pinPOT, INPUT);
  pinMode(pinUmid, OUTPUT);
  pinMode(pinAr, OUTPUT);
  pinMode(LED, OUTPUT);                   //Set pin 2 as OUTPUT
  pinMode(rele1, OUTPUT);        //  relé1 como saída
  pinMode(rele2, OUTPUT);        //  relé2 como saída
  
  pinMode(button1, INPUT_PULLDOWN);       //Set pin 13 as INPUT with pulldown
  attachInterrupt(button1, isr, RISING);  //Create interruption on pin 13

  WiFi.begin(ssid, password);             //Start wifi connection
  Serial.print("Conectando...");
  while (WiFi.status() != WL_CONNECTED) { //Check for the connection
    delay(500);
    Serial.print(".");
  }

  Serial.print("Conectado com o IP: ");
  Serial.println(WiFi.localIP());
  Actual_Millis = millis();               //Save time for refresh loop
  Previous_Millis = Actual_Millis; 
}

void loop() {  
  
  //We make the refresh loop using millis() so we don't have to sue delay();
  Actual_Millis = millis();
  if(Actual_Millis - Previous_Millis > refresh_time){
    Previous_Millis = Actual_Millis;

    u= (analogRead(pinPOT)*100)/4095;
    t= dht.readTemperature();
    
    if(WiFi.status()== WL_CONNECTED){                   //Check WiFi connection status  
      HTTPClient http;                                  //Create new client
      
      Serial.print("Temperatura ");
      Serial.print(t);
      Serial.println (" ºC ");
      situacaoUR();
      
      if(toggle_pressed){                               //If button was pressed we send text: "toggle_LED"
        data_to_send = "toggle_LED=" + LED_id;  
        toggle_pressed = false;                         //Also equal this variable back to false 
      }
      else{
        data_to_send = "check_LED_status=" + LED_id;    //If button wasn't pressed we send text: "check_LED_status"
      }
      
      //Begin new connection to website       
      http.begin("https://fivetech5.000webhostapp.com/conn_button.php");   //Indicate the destination webpage 
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");         //Prepare the header
      
      int response_code = http.POST(data_to_send);                                //Send the POST. This will giveg us a response code
      
      //If the code is higher than 0, it means we received a response
      if(response_code > 0){
        Serial.println("HTTP code " + String(response_code));                     //Print return code
  
        if(response_code == 200){                                                 //If code is 200, we received a good response and we can read the echo data
          String response_body = http.getString();                                //Save the data comming from the website
          Serial.print("Server reply: ");                                         //Print data to the monitor for debug
          Serial.println(response_body);

          penultimaLeitura = ultimaLeitura;
          ultimaLeitura = u;
          if (penultimaLeitura != ultimaLeitura){
              envio_controle();
          }

          //If the received data is LED_is_off, we set LOW the LED pin
          if(response_body == "LED_is_off"){
            digitalWrite(LED, LOW);
            digitalWrite(pinUmid, LOW);
            digitalWrite(pinAr, LOW); 
            digitalWrite(rele1, HIGH); // Ligar o relé
            digitalWrite(rele2, HIGH); // Ligar o relé
            Serial.println("Sistema Desligado...");
            situacaoUR();
            
          }
          //If the received data is LED_is_on, we set HIGH the LED pin
          else if(response_body == "LED_is_on"){
            digitalWrite(LED, HIGH);
            Serial.println("Sistema Ligado...");
            
            controleUR();
          }  
        }//End of response_code = 200
      }//END of response_code > 0
      
      else{
       Serial.print("Error sending POST, code: ");
       Serial.println(response_code);
      }
      http.end();                                                                 //End the connection
    }//END of WIFI connected
    else{
      Serial.println("WIFI connection error");
    }
  }
}

void controleUR(){
  if(u < 50){                           //abaixo de 50%
    digitalWrite(pinUmid, HIGH);             //ligar umidifcador
    digitalWrite(pinAr, LOW);                 //ar desligado
    
    digitalWrite(rele1, HIGH); // Ligar o relé umidificador
    digitalWrite(rele2, LOW); // Desligar o relé ar (lâmpada)
    
  }  else if (u >= 50 && u < 60){        //a partir de 55% e menos de 60%
    digitalWrite(pinUmid, LOW);                //umidificador desligado
    digitalWrite(pinAr, LOW);                 //ar desligado
    
    digitalWrite(rele1, LOW);  // Desligar o relé umidificador
    digitalWrite(rele2, LOW); // Desligar o relé ar (lâmpada)
    
  }else{                     //a partir de 60%
    digitalWrite(pinAr, HIGH);               //ar ligado
    digitalWrite(pinUmid, LOW);             //umidificador desligado
    
    digitalWrite(rele1, LOW);  // Desligar o relé umidificador
    digitalWrite(rele2, HIGH); // Ligar o relé ar (lâmpada)    
  }
}

void situacaoUR(){
   if(u < 50){                           //abaixo de 50%
    Serial.print(F("Umidade: "));
    Serial.print(u);
    Serial.print(F("%   UMIDADE BAIXA. UMIDIFICADOR LIGADO!"));
    
  }else if(u>=50 && u < 60){        //a partir de 55% e menos de 60%
    Serial.print(F("Umidade: "));
    Serial.print(u);
    Serial.println(F("%   UMIDADE IDEAL. AMBOS DESLIGADOS!"));
    
  }else{                     //a partir de 60%
    Serial.print(F("Umidade: "));
    Serial.print(u);
    Serial.println(F("%   UMIDADE ALTA. AR CONDICIONADO LIGADO!"));
  }
}

void envio_controle(){

      HTTPClient http; 
      data_to_send = "enviar_insert=true&u="+ String(u) +"&t=" + String(t);
      
      http.begin("https://fivetech5.000webhostapp.com/conexao.php");   //página de destino
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");         

      int response_code = http.POST(data_to_send);                                

      Serial.println("HTTP code " + String(response_code));                    
                                             
      String response_body = http.getString();                                //Save the data comming from the website
      Serial.print("Server reply: ");                                         //Print data to the monitor for debug
      Serial.println(response_body);

      http.end();    
}
