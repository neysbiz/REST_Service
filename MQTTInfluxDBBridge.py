import re
from typing import NamedTuple

import paho.mqtt.client as mqtt
from influxdb import InfluxDBClient

INFLUXDB_ADDRESS = 'localhost' #'192.168.0.8'
INFLUXDB_USER = 'mqtt'
INFLUXDB_PASSWORD = 'mqtt'
INFLUXDB_DATABASE = 'Project1'

MQTT_ADDRESS = '192.168.1.213' #'192.168.0.8'
MQTT_USER = 'kessel2'
MQTT_PASSWORD = 'kessel2'
MQTT_TOPIC = 'board/+'
MQTT_REGEX = 'board/([^/]+)'
MQTT_CLIENT_ID = 'MQTTInfluxDBBridge'

influxdb_client = InfluxDBClient(INFLUXDB_ADDRESS, 8086, INFLUXDB_USER, INFLUXDB_PASSWORD, None)

class SensorData(NamedTuple):
	game: str
	team: str
	value: str
#    location: str
#    measurement: str
#    value: float

def on_connect(client, userdata, flags, rc):
    """ The callback for when the client receives a CONNACK response from the server."""
    print('Connected with result code ' + str(rc))
    client.subscribe(MQTT_TOPIC)
    print('topic ' + str(MQTT_TOPIC))
    match = re.match(MQTT_REGEX, 'board')
    print(match)

def _parse_mqtt_message(topic, payload):
	print('topic' + str(topic))
	print(payload)
	match = re.match(MQTT_REGEX, topic)
	if match:
		game = 'neu'
		team = match.group(1)
#        location = match.group(1)
#        measurement = match.group(2)
		if measurement == 'status':
            		return None
		return SensorData(game, team, str(payload))
	else:
		return SensorData('none', 'none', 'none')

def _send_sensor_data_to_influxdb(sensor_data):
    json_body = [
        {
            'game': sensor_data.game,
            'tags': {
                'team': sensor_data.team
            },
            'fields': {
                'value': sensor_data.value
            }
        }
    ]
    influxdb_client.write_points(json_body)

def on_message(client, userdata, msg):
    """The callback for when a PUBLISH message is received from the server."""
    print(msg.topic + ' ' + str(msg.payload))
    sensor_data = _parse_mqtt_message(msg.topic, msg.payload.decode('utf-8'))
    if sensor_data is not None:
        _send_sensor_data_to_influxdb(sensor_data)

def _init_influxdb_database():
    databases = influxdb_client.get_list_database()
    if len(list(filter(lambda x: x['name'] == INFLUXDB_DATABASE, databases))) == 0:
        influxdb_client.create_database(INFLUXDB_DATABASE)
    influxdb_client.switch_database(INFLUXDB_DATABASE)

def main():
    _init_influxdb_database()

    mqtt_client = mqtt.Client(MQTT_CLIENT_ID)
    mqtt_client.username_pw_set(MQTT_USER, MQTT_PASSWORD)
    mqtt_client.on_connect = on_connect
    mqtt_client.on_message = on_message

    mqtt_client.connect(MQTT_ADDRESS, 1883)
    mqtt_client.loop_forever()


if __name__ == '__main__':
    print('MQTT to InfluxDB bridge')
    main()
