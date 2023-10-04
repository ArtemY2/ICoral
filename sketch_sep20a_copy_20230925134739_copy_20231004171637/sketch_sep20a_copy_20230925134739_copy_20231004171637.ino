#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <DHT_U.h>
#include <ESP8266HTTPClient.h>

const char* ssid = "DLive_04C1";
const char* password = "62CEED04C0";

#define DHTPIN D6
#define DHTTYPE DHT11

DHT_Unified dht(DHTPIN, DHTTYPE);
float temperature = 0.0;
float humidity = 0.0;
int waterLevel = 0;

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
  server.on("/", HTTP_POST, handleRoot);
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
  HTTPClient http;
  WiFiClient client;
  String url = "http://innoreef.pe.kr/index.php";
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
