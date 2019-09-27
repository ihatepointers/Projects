from keras.models import load_model

import os
import librosa
import IPython.display as ipd
import matplotlib.pyplot as plt
import numpy as np
from scipy.io import wavfile
import warnings
import time
import random



warnings.filterwarnings("ignore")

model=load_model('best_model.hdf5')

from sklearn.preprocessing import LabelEncoder
labels=[]
le = LabelEncoder()
y=le.fit_transform(labels)
classes= list(le.classes_)

def predict(audio):
    prob=model.predict(audio.reshape(1,18560,1))
    index=np.argmax(prob[0])
    return (str(classes[index]) + " " + str(prob[0][index]))

train_audio_path = 'path/to/train'
predict_audio_path = 'path/to/audio/file'

while True:
    while(len(os.listdir(predict_audio_path)) == 0):
        print("Waiting for file...")
        time.sleep(0.1)

    fileName = os.listdir(predict_audio_path)[0];
    filePath = predict_audio_path+'/'+fileName

    samples, sample_rate = librosa.load(filePath, sr = 8000)

    scale = 18560.0/len(samples)

    newSampleRate = scale*8000

    samples = librosa.resample(samples, sample_rate, newSampleRate)

    if(len(samples) != 18560):
        if (len(samples) > 18560):
            samples = samples[:-1]
            all_wave.append(samples)
            all_label.append(label)
            print(train_audio_path + '/' + label + '/' + wav + " trimmed")
        else:
            while (len(samples) < 18560):
                samples.apppend(0)
            all_wave.append(samples)
            all_label.append(label)
            print(train_audio_path + '/' + label + '/' + wav + " padded")





    ipd.Audio(samples, rate=sample_rate)
    randomFileName = str(random.randint(1,999999)) + ".wav"
    print("New sound file detected! Speech-to-Text guess:",predict(samples))