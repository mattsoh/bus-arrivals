import requests, json
import os
from dotenv import load_dotenv
load_dotenv()
url = "http://datamall2.mytransport.sg/ltaodataservice/BusStops"

payload = {}
headers = {
  'AccountKey': os.getenv("API_KEY")
}
response = requests.request("GET", url, headers=headers, data=payload, timeout=60)
a = json.loads(response.text)
d = dict()
for i in a["value"]:
    stop = i['BusStopCode']
    i.pop('BusStopCode',None)
    d[stop] = i
print(len(d.keys()))
