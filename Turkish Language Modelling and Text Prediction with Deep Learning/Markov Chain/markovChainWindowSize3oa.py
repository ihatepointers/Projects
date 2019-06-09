import numpy as np
import time
start=time.time()

with open("testDataMaskedAnswers.txt", encoding="utf8") as a:
    contentAnswers = a.readlines()

with open("markovPairs.txt", encoding="utf8") as r:
    contentPairs = r.readlines()

with open("markovFrequency.txt", encoding="utf8") as d:
    contentFrequency = d.readlines()

with open("vocab.txt", encoding="utf8") as v:
    contentVocab = v.readlines()

with open("testDataMasked.txt", encoding="utf8") as f:
    contentData = f.readlines()

contentData = [x.strip() for x in contentData]
contentVocab = [x.strip() for x in contentVocab]
contentFrequency = [x.strip() for x in contentFrequency]
contentPairs = [x.strip() for x in contentPairs]
contentAnswers = [x.strip() for x in contentAnswers]

vocabArray = []

rowArray = []
colArray = []
frequencyArray = []
answerArray = []

myDict = {}

for line in contentAnswers:
    answerArray.append(line)

for line in contentVocab:
    vocabArray.append(line)

for line in contentFrequency:
    tmpArray = line.split()
    for tmpData in tmpArray:
        frequencyArray.append(tmpData)

dictIndex = 0
for line in contentPairs:
    tmpArray = line.split()
    for tmpTuple in tmpArray:
        tmpTupleArray = tmpTuple.split(',')
        myDict[tmpTupleArray[0], tmpTupleArray[1]] = frequencyArray[dictIndex]
        dictIndex += 1
lineIndex = 1
with open("markovWindowSize3oaResult.txt", 'a', encoding="utf8") as markovResultFile:
    for line in contentData:
        tmpArray = line.split()

        maskedIndex = tmpArray.index('[MASK]')

        for i in range(len(tmpArray)):
            if (i != maskedIndex):
                try:
                    tmpArray[i] = contentVocab.index(tmpArray[i])
                except:
                    print("kelime bulunamadi")
            i += 1

        bestGuess = "----"
        bestGuessChance = 0
        i = 0

        guessArray = []

        for key1, key2 in myDict:
            if (int(key1) == tmpArray[maskedIndex - 1]):
                if (maskedIndex > 2):
                    if (((str(tmpArray[maskedIndex - 3]), str(tmpArray[maskedIndex - 2])) in myDict) and (
                            (str(tmpArray[maskedIndex - 2]), str(tmpArray[maskedIndex - 1])) in myDict)):
                        bestGuess = contentVocab[int(key2)]
                        bestGuessChance = int(
                            myDict[str(tmpArray[maskedIndex - 2]), str(tmpArray[maskedIndex - 1])]) * int(
                            myDict[key1, key2]) * int(
                            myDict[str(tmpArray[maskedIndex - 3]), str(tmpArray[maskedIndex - 2])])
                        if (maskedIndex < len(tmpArray) - 1):
                            if ((key2, str(tmpArray[maskedIndex + 1])) in myDict):
                                bestGuessChance = bestGuessChance * int(myDict[key2, str(tmpArray[maskedIndex + 1])])
                        guessArray.append([bestGuess, bestGuessChance])
                    elif ((str(tmpArray[maskedIndex - 2]), str(tmpArray[maskedIndex - 1])) in myDict):
                        bestGuess = contentVocab[int(key2)]
                        bestGuessChance = int(
                            myDict[str(tmpArray[maskedIndex - 2]), str(tmpArray[maskedIndex - 1])]) * int(
                            myDict[key1, key2])
                        if (maskedIndex < len(tmpArray) - 1):
                            if ((key2, str(tmpArray[maskedIndex + 1])) in myDict):
                                bestGuessChance = bestGuessChance * int(myDict[key2, str(tmpArray[maskedIndex + 1])])
                        guessArray.append([bestGuess, bestGuessChance])
                    else:
                        bestGuess = contentVocab[int(key2)]
                        bestGuessChance = int(myDict[key1, key2])
                        if (maskedIndex < len(tmpArray) - 1):
                            if ((key2, str(tmpArray[maskedIndex + 1])) in myDict):
                                bestGuessChance = bestGuessChance * int(myDict[key2, str(tmpArray[maskedIndex + 1])])
                        guessArray.append([bestGuess, bestGuessChance])

                elif (maskedIndex > 1):
                    bestGuess
                    if ((str(tmpArray[maskedIndex - 2]), str(tmpArray[maskedIndex - 1])) in myDict):
                        bestGuess = contentVocab[int(key2)]
                        bestGuessChance = int(
                            myDict[str(tmpArray[maskedIndex - 2]), str(tmpArray[maskedIndex - 1])]) * int(
                            myDict[key1, key2])
                        if (maskedIndex < len(tmpArray) - 1):
                            if ((key2, str(tmpArray[maskedIndex + 1])) in myDict):
                                bestGuessChance = bestGuessChance * int(myDict[key2, str(tmpArray[maskedIndex + 1])])
                        guessArray.append([bestGuess, bestGuessChance])
                    else:
                        bestGuess = contentVocab[int(key2)]
                        bestGuessChance = int(myDict[key1, key2])
                        if (maskedIndex < len(tmpArray) - 1):
                            if ((key2, str(tmpArray[maskedIndex + 1])) in myDict):
                                bestGuessChance = bestGuessChance * int(myDict[key2, str(tmpArray[maskedIndex + 1])])
                        guessArray.append([bestGuess, bestGuessChance])




                else:
                    bestGuess = contentVocab[int(key2)]
                    bestGuessChance = int(myDict[key1, key2])
                    if (maskedIndex < len(tmpArray) - 1):
                        if ((key2, str(tmpArray[maskedIndex + 1])) in myDict):
                            bestGuessChance = bestGuessChance * int(myDict[key2, str(tmpArray[maskedIndex + 1])])
                    guessArray.append([bestGuess, bestGuessChance])

        guessArraysorted = sorted(guessArray, key=lambda item: item[1], reverse=True)[:5]
		#Top-1 icin: guessArraysorted = guessArraysorted[:1]
        txt = ""
        if (len(guessArraysorted) > 0):
            for guess in guessArraysorted:
                txt += guess[0] + " "
            txt += "\n"
        else:
            txt += "----\n"
        print(str(lineIndex) +". " + str(line) + "\nPredictions: " + str(txt))
        lineIndex+=1
        markovResultFile.write(txt)

end = time.time()
print("Total exec time:")
print(end-start)

