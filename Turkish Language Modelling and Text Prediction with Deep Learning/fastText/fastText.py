from pyfasttext import FastText
import numpy as np
import time

start = time.time()

model = FastText('output.bin')

with open("testDataMasked.txt", encoding="utf8") as f:
    contentData = f.readlines()

contentData = [x.strip() for x in contentData]
lineIndex = 1
with open("FastTextResult.txt", 'a', encoding="utf8") as fastTextResultFile:
	for line in contentData:
		tmpArray = line.split()
		vectors = []
		maskedIndex = tmpArray.index('[MASK]')
		
		
		similiarsLeft = model.nearest_neighbors(tmpArray[maskedIndex-1], k=1000)
		if(maskedIndex < len(tmpArray)-1):
			similiarsRight = model.nearest_neighbors(tmpArray[maskedIndex+1], k=1000)
		

		

		predictions = []
		if(maskedIndex < len(tmpArray)-1):
			for simL in similiarsLeft:
				index = 0
				for simR in similiarsRight:
					if(simL[0] == simR[0]):
						predictions.append(simL[0])
		else:
			for simL in similiarsLeft:
				predictions.append(simL[0])
		
		if(len(predictions) > 5):
			predictions = predictions[:5]
			#Top-1 prediction icin: predictions = predictions[:1]
		tmpText = ""
		for pred in predictions:
			tmpText += str(pred) +","
		tmpText+= "\n"
		fastTextResultFile.write(tmpText)
		print(str(lineIndex)+". " + str(line) + "\nPredictions: " +tmpText)
		lineIndex += 1




end = time.time()
print(end - start)