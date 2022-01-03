In this repository, you can find 3 approaches for text prediction.
All approaches require a big corpus. Additionally, BERT and fastText require a trained language model to work with.
If you need more information on how to create models, you can refer to https://github.com/google-research/bert and https://github.com/facebookresearch/fastText repositories respectively.

- Markov Chain: The most simplistic approach. You need to find chains of words with their frequency in a given corpus.
- fastText: Creates a vectors for words. You should get better results than Markov Chain in general.
- BERT: The best approach out of all 3 for text prediction. Evaluates words in a given context through deep learning.
