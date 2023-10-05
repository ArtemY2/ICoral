#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <DHT.h>

const char* ssid = "DLive_3852";
const char* password = "E0012D3851";

#define WATER_LEVEL_PIN A0
#define DHT_PIN D6

DHT dht(DHT_PIN, DHT11);

void setup() {
  Serial.begin(115200);
  delay(10);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("Wi-Fi connected");
  dht.begin();
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    int waterLevel = analogRead(WATER_LEVEL_PIN); 
    float temperature = dht.readTemperature(); 
    float humidity = dht.readHumidity(); 

    WiFiClient client;
    HTTPClient http;
    http.begin(client, "http://icoral.dothome.co.kr/index.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String postData = "temperature=" + String(temperature) + "&humidity=" + String(humidity) + "&waterLevel=" + String(waterLevel);

    int httpResponseCode = http.POST(postData);
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
      Serial.print("Response: ");
      Serial.println(response);
    } else {
      Serial.println("Error sending POST request");
    }
    http.end();
  }
  delay(60000 * 60);
}
