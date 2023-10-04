#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <DHT_U.h>
#include <ESP8266HTTPClient.h>

const char* ssid = "5G8664";
const char* password = "88888888";

#define DHTPIN D6
#define DHTTYPE DHT11

DHT_Unified dht(DHTPIN, DHTTYPE);
float temperature;
float humidity;

const int waterLevelPin = A0;

ESP8266WebServer server(80);

void setup() {
  Serial.begin(115200);
  delay(10);

  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("Wi-Fi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  server.on("/", HTTP_GET, handleRoot);

  server.begin();
}

void loop() {
  server.handleClient();
  sensors_event_t event;
  dht.temperature().getEvent(&event);
  if (!isnan(event.temperature)) {
    temperature = event.temperature;
  }
  dht.humidity().getEvent(&event);
  if (!isnan(event.relative_humidity)) {
    humidity = event.relative_humidity;
  }
}

void handleRoot() {
  String html = "<html><head>";
  html += "<style>";
  html += "body { font-family: Arial, sans-serif; text-align: center; background-color: #f2f2f2; margin: 0; padding: 0; }";
  html += ".container { background-color: white; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); padding: 20px; }";
  html += "h1 { color: #333; }";
  html += ".sensor-data { font-size: 24px; text-align: left; margin-bottom: 20px; padding: 10px; }";
  html += "</style>";
  html += "</head><body>";
  html += "<div class='container'>";
  html += "<h1>ESP8266 Weather Station</h1>";
  html += "<div class='sensor-data'>";
  html += "Temperature: " + String(temperature) + "°C<br>";
  html += "Humidity: " + String(humidity) + "%<br>";
  int waterLevel = analogRead(waterLevelPin);
  html += "Water Level: " + String(waterLevel) + "<br>";
  html += "</div>";
  html += "</div>";
  html += "</body></html>";
  server.send(200, "text/html", html);

  // Отправляем данные на сервер с использованием HTTP POST запроса
  HTTPClient http;
  WiFiClient client;
  String url = "http://innoreef.pe.kr/html/index.php";
  http.begin(client, url);
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
