import torch
from pytorch_pretrained_bert import BertTokenizer, BertModel, BertForMaskedLM
import time
BERT_MODEL_PATH = 'model/'
WORK_DIR = "working/"

bert_version = WORK_DIR
model = BertForMaskedLM.from_pretrained(WORK_DIR)

tokenizer = BertTokenizer.from_pretrained(BERT_MODEL_PATH)
start = time.time()
with open("testDataMasked.txt", encoding="utf8") as f:
    contentData = f.readlines()

lineIndex = 1

with open("bertResult.txt", 'a', encoding="utf8") as bertResultFile:
    for line in contentData:

        text = line
        text = "[CLS] " + line
        text += " [SEP]"

        
        tokenized_text = tokenizer.tokenize(text)
        mask_positions = []
        for i in range(len(tokenized_text)):
            if tokenized_text[i] == '[MASK]':
                mask_positions.append(i)

        model.eval()
        for mask_pos in mask_positions:
            token_ids = tokenizer.convert_tokens_to_ids(tokenized_text)
            tokens_tensor = torch.tensor([token_ids])
            predictions = model(tokens_tensor)[0, mask_pos]

            a = []
            for pred in predictions:
                a.append(pred.item())
                
            predicted_token_ids = []
            b = sorted(range(len(a)), key=lambda i: a[i], reverse=True)[:31999]
            i=0
            while(len(predicted_token_ids) < 5):
                if((tokenizer.convert_ids_to_tokens([b[i]])[0][0]) != '#' ):
                    predicted_token_ids.append(b[i])
                i+=1
            if(len(predicted_token_ids )> 5):
                predicted_token_ids = predicted_token_ids[:1]
            i=0
            max=0
            maxIndex = 0
            for i in range(len(a)):
                if(a[i] > max):
                    tmpText = tokenizer.convert_ids_to_tokens([i])[0]
                    if(tmpText[0] !='#'):
                        max = a[i]
                        maxIndex = i
                    
            predicted_tokens = []
            for value in predicted_token_ids:
                predicted_tokens.append(tokenizer.convert_ids_to_tokens([value])[0])
            predicted_token = tokenizer.convert_ids_to_tokens([maxIndex])[0]
            tokenized_text[mask_pos] = predicted_token
			
		#Top-1 prediction icin: predicted_tokens = predicted_tokens[:1]
        tmpText2 =""
        for predtok in predicted_tokens:
            tmpText2 += str(predtok)
        
        print(str(lineIndex) + ". " + str(line) + "Predictions: " + tmpText2)
        print("\n\n")
        tmpText2 += "\n"
        lineIndex += 1
        bertResultFile.write(tmpText2)
        
end = time.time()
print(end - start)
