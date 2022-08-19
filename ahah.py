#!/usr/bin/env python3
import os,sys
import json

with open("./data.json") as f:
	data=json.load(f)

data=[(i+1,song[6]) for i,song in enumerate(data) if len(song)==7 and i>=66]

for i,comment in data:
	comment = comment.replace("'","\\'").replace('–','-')
	print(f"INSERT INTO Comments VALUES ({i},'{comment}');")