import requests, json

url = "http://datamall2.mytransport.sg/ltaodataservice/BusStops"

payload = {}
headers = {
  ''
}

response = requests.request("GET", url, headers=headers, data=payload)
a = json.loads(response.text)
print(a["value"][-1])
