You can find two python files for training and making predictions named train.py and predict.py respectively.
- train.py: Requires a big set of audio files categorized in their respective class folders. After the training, a .hdf5 model should appear in your output folder.
- predict.py: Uses the previously created model with an input audio file to categorize the audio. You can split a given speech audio to letters and numbers and make a prediction for them sequentially to turn speech to text.
