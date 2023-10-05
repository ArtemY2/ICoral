#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

const char* ssid = "DLive_3852";
const char* password = "E0012D3851";

#define WATER_LEVEL_PIN A0

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
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    int waterLevel = analogRead(WATER_LEVEL_PIN); // Считываем уровень воды

    WiFiClient client;
    HTTPClient http;
    http.begin(client, "http://icoral.dothome.co.kr/index.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String postData = "temperature=25&humidity=50&waterLevel=" + String(waterLevel);

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
  delay(60000 * 20);
}
