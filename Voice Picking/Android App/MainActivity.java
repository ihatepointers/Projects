package com.example.speechrec;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.Manifest;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.media.AudioManager;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.speech.RecognitionListener;
import android.speech.RecognizerIntent;
import android.speech.SpeechRecognizer;
import android.speech.tts.TextToSpeech;
import android.speech.tts.UtteranceProgressListener;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Locale;

import org.javatuples.Quintet;
import org.javatuples.Sextet;
import org.ksoap2.SoapEnvelope;
import org.ksoap2.serialization.Marshal;
import org.ksoap2.serialization.PropertyInfo;
import org.ksoap2.serialization.SoapObject;
import org.ksoap2.serialization.SoapPrimitive;
import org.ksoap2.serialization.SoapSerializationEnvelope;
import org.ksoap2.transport.HttpTransportSE;
import org.kxml2.kdom.Element;
import org.kxml2.kdom.Node;
import org.javatuples.Quartet;


public class MainActivity extends AppCompatActivity {

    TextView resultText;
    private TextToSpeech mTTS;
    List<HashMap> messageList = new ArrayList<HashMap>();
    ImageView logo;
    public ArrayList<Quartet<String, String, String, Integer>> orders = new ArrayList<Quartet<String, String, String, Integer>>();
    public ArrayList<Sextet<Integer, String, String, Integer, String, Integer>> items = new ArrayList<Sextet<Integer, String, String, Integer, String, Integer>>();

    HashMap<String, String> ttsMap = new HashMap<String, String>();

    RecyclerViewAdapter messageAdapter;
    RecyclerViewAdapterOrder orderAdapter;
    RecyclerViewAdapterItem itemAdapter;

    RecyclerView recyclerViewMessages;
    RecyclerView recyclerViewOrders;
    RecyclerView recyclerViewItems;

    int MY_PERMISSIONS_RECORD_AUDIO;
    int stage = 0;
    long mSpeechRecognizerStartListeningTime = 0;

    boolean ttsSpeaking;

    static RecognitionListener listener = null;

    Context context;

    AudioManager mAudioManager;
    AudioManager.OnAudioFocusChangeListener afChangeListener;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        ttsSpeaking = false;


        mAudioManager =(AudioManager)getSystemService(Context.AUDIO_SERVICE);


        context = this.getApplicationContext();

        recyclerViewMessages = findViewById(R.id.recyclerView);
        recyclerViewOrders = findViewById(R.id.recyclerViewOrder);
        recyclerViewItems = findViewById(R.id.recyclerViewItem);

        recyclerViewItems.setVisibility(View.GONE);

        logo = (ImageView) findViewById(R.id.ikon6);

        if (ContextCompat.checkSelfPermission(this,
                Manifest.permission.RECORD_AUDIO)
                != PackageManager.PERMISSION_GRANTED) {

            if (ActivityCompat.shouldShowRequestPermissionRationale(this,
                    Manifest.permission.RECORD_AUDIO)) {
            } else {
                ActivityCompat.requestPermissions(this,
                        new String[]{Manifest.permission.RECORD_AUDIO},
                        MY_PERMISSIONS_RECORD_AUDIO);
            }
        }



        initRecyclerView();
        initRecyclerViewOrder();
        initRecyclerViewItem();

        final Intent intent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
        intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL,
                RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
        intent.putExtra(RecognizerIntent.EXTRA_CALLING_PACKAGE,
                "com.domain.app");



        final SpeechRecognizer recognizer = SpeechRecognizer
                .createSpeechRecognizer(context.getApplicationContext());

        mAudioManager.setStreamMute(AudioManager.STREAM_MUSIC, true);

        mTTS = new TextToSpeech(this, new TextToSpeech.OnInitListener(){
            @Override
            public void onInit(int status){
                if (status == TextToSpeech.SUCCESS){
                    int result = mTTS.setLanguage(Locale.getDefault());

                    if (result == TextToSpeech.LANG_MISSING_DATA
                            || result == TextToSpeech.LANG_NOT_SUPPORTED){

                        Log.e("TTS","Language not supported");
                    }

                }
            }
        });


        mTTS.setOnUtteranceProgressListener(new UtteranceProgressListener() {
            @Override
            public void onStart(String utteranceId) {
                Log.d("stt", "mTTS started");
                mAudioManager.setStreamMute(AudioManager.STREAM_MUSIC, false);
            }

            @Override
            public void onDone(String utteranceId) {
                    Handler mainHandler = new Handler(getApplicationContext().getMainLooper());

                    Runnable myRunnable = new Runnable() {
                        @Override
                        public void run() {
                            mAudioManager.setStreamMute(AudioManager.STREAM_MUSIC, true);
                            Log.d("stt", "mTTS finished");
                            recognizer.stopListening();
                            recognizer.destroy();
                            recognizer.setRecognitionListener(listener);
                            recognizer.startListening(intent);
                        }
                    };
                    mainHandler.post(myRunnable);
            }

            @Override
            public void onError(String utteranceId) {

            }
        });



        new SoapCall("siparişleri listele").execute();


        MainActivity.this.runOnUiThread(new Runnable() {
            public void run() {



                listener = new RecognitionListener() {
                    @Override
                    public void onResults(Bundle results) {
                        ArrayList<String> voiceResults = results
                                .getStringArrayList(SpeechRecognizer.RESULTS_RECOGNITION);
                        if (voiceResults == null) {
                            Log.d("stt","No voice results");
                            System.out.println("No voice results");
                        } else {
                            Log.d("stt","Printing matches: ");
                            System.out.println("Printing matches: ");
                            for (String match : voiceResults) {
                                Log.d("stt",match);
                                System.out.println(match);
                            }

                            new SoapCall(voiceResults.get(0)).execute();
                        }

                    }

                    @Override
                    public void onReadyForSpeech(Bundle params) {
                        Log.d("stt", "Ready for speech");
                        System.out.println("Ready for speech");
                    }

                    /**
                     *  ERROR_NETWORK_TIMEOUT = 1;
                     *  ERROR_NETWORK = 2;
                     *  ERROR_AUDIO = 3;
                     *  ERROR_SERVER = 4;
                     *  ERROR_CLIENT = 5;
                     *  ERROR_SPEECH_TIMEOUT = 6;
                     *  ERROR_NO_MATCH = 7;
                     *  ERROR_RECOGNIZER_BUSY = 8;
                     *  ERROR_INSUFFICIENT_PERMISSIONS = 9;
                     *
                     * @param error code is defined in SpeechRecognizer
                     */
                    @Override
                    public void onError(int error) {

                        System.err.println("Error listening for speech: " + error);
                        Log.d("stt", "Error listening for speech: " + error);
                        recognizer.stopListening();
                        recognizer.destroy();
                        recognizer.setRecognitionListener(listener);
                        recognizer.startListening(intent);

                    }

                    @Override
                    public void onBeginningOfSpeech() {
                        System.out.println("Speech starting");
                        Log.d("stt", "Speech starting");
                    }

                    @Override
                    public void onBufferReceived(byte[] buffer) {
                        // TODO Auto-generated method stub

                    }

                    @Override
                    public void onEndOfSpeech() {
                        // TODO Auto-generated method stub

                    }

                    @Override
                    public void onEvent(int eventType, Bundle params) {
                        // TODO Auto-generated method stub

                    }

                    @Override
                    public void onPartialResults(Bundle partialResults) {
                        // TODO Auto-generated method stub

                    }

                    @Override
                    public void onRmsChanged(float rmsdB) {
                        // TODO Auto-generated method stub

                    }
                };


                recognizer.setRecognitionListener(listener);
                recognizer.startListening(intent);

            }
        });



        logo.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                getSpeechIput(v);
            }
        });




    }

    private void initRecyclerView(){
        messageAdapter = new RecyclerViewAdapter(this, messageList);
        recyclerViewMessages.setAdapter(messageAdapter);
        recyclerViewMessages.setLayoutManager(new LinearLayoutManager(this));
    }

    private void initRecyclerViewOrder(){
        orderAdapter = new RecyclerViewAdapterOrder(this, orders);
        recyclerViewOrders.setAdapter(orderAdapter);
        recyclerViewOrders.setLayoutManager(new LinearLayoutManager(this));
    }

    private void initRecyclerViewItem(){
        itemAdapter = new RecyclerViewAdapterItem(this, items);
        recyclerViewItems.setAdapter(itemAdapter);
        recyclerViewItems.setLayoutManager(new LinearLayoutManager(this));
    }

    private class SoapCall extends AsyncTask<String, Object,String> {

        ProgressBar pb;

        public static final String NAMESPACE = "http://tempuri.org";
        public static final String URL = "http://localhost:8000/GettingStarted/CalculatorService/";
        public static final String SOAP_ACTION = "http://tempuri.org/ICalculator/ProcessText";
        public static final String METHOD_NAME = "ProcessText";
        public int timeOut = 600000;
        public String requestText = "";
        Exception exception = null;

        String response;

        public SoapCall(String text){
            requestText = text;
        }

        @Override
        protected void onPreExecute(){
            super.onPreExecute();

            HashMap<String, Integer> message = new HashMap<String, Integer>();
            message.put(requestText, 1);
            messageList.add(message);


        }


        @Override
        protected String doInBackground(String... strings) {

            SoapObject request = new SoapObject(NAMESPACE,METHOD_NAME);
            request.addProperty("text", requestText);
            

            SoapSerializationEnvelope envelope = new SoapSerializationEnvelope(SoapEnvelope.VER11);
            envelope.dotNet = true;
            envelope.implicitTypes = true;
            envelope.encodingStyle = SoapSerializationEnvelope.XSD;
            envelope.setOutputSoapObject(request);

            
            Element[] header = new Element[1];

            header[0] = new Element().createElement(NAMESPACE, "GetText");
            header[0].addChild(Node.TEXT, "HeaderTextContent");

            envelope.headerOut = header;
            //////////////////////////////





            HttpTransportSE transportSE = new HttpTransportSE(URL, timeOut);

            transportSE.debug = true;
            transportSE.setXmlVersionTag("<?xml version=\"1.0\" encoding=\"utf-8\"?>");

            try{
                System.out.println(transportSE.requestDump);
                transportSE.call(SOAP_ACTION,envelope);
                SoapObject soapResult = (SoapObject) envelope.bodyIn;

                response = ((SoapObject)soapResult.getProperty(0)).getPropertyAsString(0);

                int finished = 0;
                int counter = 2;

                try{
                    stage = Integer.valueOf(((SoapObject)soapResult.getProperty(0)).getPropertyAsString(0));
                    response = ((SoapObject)soapResult.getProperty(0)).getPropertyAsString(1);
                }catch (Exception e){
                    exception = e;
                }

                switch(stage){
                    case 0 :




                        while(finished == 0){
                            try{
                                String str = ((SoapObject)soapResult.getProperty(0)).getPropertyAsString(counter);
                                String[] stringList = str.split("\\|");
                                Quartet<String, String, String, Integer> order = new Quartet<String, String, String, Integer>(stringList[0], stringList[1], stringList[2], Integer.parseInt(stringList[3]));
                                orders.add(order);
                                counter++;
                            }catch (Exception e){
                                finished = 1;
                                exception = e;
                            }
                        }
                        break;


                    case 1 :

                        while(finished == 0){
                            try{
                                String str = ((SoapObject)soapResult.getProperty(0)).getPropertyAsString(counter);
                                String[] stringList = str.split("\\|");
                                Sextet<Integer, String, String, Integer, String, Integer> item = new Sextet<Integer, String, String, Integer, String, Integer>(Integer.parseInt(stringList[0]), stringList[1], stringList[2], Integer.parseInt(stringList[3]), stringList[4], 0);
                                items.add(item);
                                counter++;
                            }catch (Exception e){
                                finished = 1;
                                exception = e;
                            }
                        }




                        break;


                    case 2 :

                        String str = ((SoapObject)soapResult.getProperty(0)).getPropertyAsString(counter);
                        int i = Integer.valueOf(str);
                        if(i > -1){
                            Sextet<Integer, String, String, Integer, String, Integer> item = new Sextet<Integer, String, String, Integer, String, Integer>(items.get(i).getValue0(), items.get(i).getValue1(), items.get(i).getValue2(), items.get(i).getValue3(), items.get(i).getValue4(), 1);
                            items.set(i, item);

                        }

                        break;


                    case 3 :

                        break;


                    case 4 :
                        String str2 = ((SoapObject)soapResult.getProperty(0)).getPropertyAsString(counter);
                        int i2 = Integer.valueOf(str2);

                        String customAmountStr = ((SoapObject)soapResult.getProperty(0)).getPropertyAsString(counter+1);
                        int customAmount = Integer.valueOf(customAmountStr);

                        if(i2 > -1){
                            Sextet<Integer, String, String, Integer, String, Integer> item = new Sextet<Integer, String, String, Integer, String, Integer>(items.get(i2).getValue0(), items.get(i2).getValue1(), items.get(i2).getValue2(), customAmount, items.get(i2).getValue4(), 2);
                            items.set(i2, item);
                        }

                        break;
                    case 5 :


                        items = new ArrayList<Sextet<Integer, String, String, Integer, String, Integer>>();
                        orders = new ArrayList<Quartet<String, String, String, Integer>>();


                        break;

                    default:




                        break;


                }



            }catch (Exception e){
                e.printStackTrace();
                Log.e("Error",e.toString());
                exception = e;
                return "Anlamadım.";
            }

            return response;
        }

        @Override
        protected void onPostExecute(String result){
            super.onPostExecute(result);

            if(result == null){
                Toast.makeText(MainActivity.this, exception.toString(), Toast.LENGTH_SHORT).show();
            }else{
                HashMap<String, Integer> message = new HashMap<String, Integer>();
                message.put(result, 0);
                messageList.add(message);

                messageAdapter.notifyDataSetChanged();
                orderAdapter.notifyDataSetChanged();
                itemAdapter.notifyDataSetChanged();

                switch(stage){

                    case 0:
                        recyclerViewItems.setVisibility(View.GONE);
                        recyclerViewOrders.setVisibility(View.VISIBLE);
                        break;

                    case 1:
                        recyclerViewOrders.setVisibility(View.GONE);
                        recyclerViewItems.setVisibility(View.VISIBLE);


                        break;

                    case 2:

                        break;

                }
                speak();
            }
        }
    }

    public void getSpeechIput(View view){
        Intent intent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
        intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL, RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
        if(intent.resolveActivity(getPackageManager()) != null){
            startActivityForResult(intent, 10);
        }else{
            Toast.makeText(this, "Your device doesn't support speech input", Toast.LENGTH_SHORT).show();
        }

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data){
        super.onActivityResult(requestCode, resultCode, data);

        switch (requestCode){
            case 10:
                if(resultCode == RESULT_OK && data != null){
                    ArrayList<String> result = data.getStringArrayListExtra(RecognizerIntent.EXTRA_RESULTS);
                    new SoapCall(result.get(0)).execute();
                }
                break;
        }
    }



    private void speak(){
        String text = messageList.get(messageList.size()-1).toString();
        int charIndex = text.indexOf("=");
        text= text.substring(0 , charIndex);
        float pitch = 1.1f;
        float speed = 2.0f;

        mTTS.setPitch(pitch);
        mTTS.setSpeechRate(speed);

        ttsMap.put(TextToSpeech.Engine.KEY_PARAM_UTTERANCE_ID, "UniqueID");

        int result = mAudioManager.requestAudioFocus(afChangeListener,
                AudioManager.STREAM_MUSIC,
                AudioManager.AUDIOFOCUS_GAIN);


        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            mTTS.speak(text, TextToSpeech.QUEUE_FLUSH, ttsMap);
        } else {
            mTTS.speak(text, TextToSpeech.QUEUE_FLUSH, ttsMap);
        }
    }


}


