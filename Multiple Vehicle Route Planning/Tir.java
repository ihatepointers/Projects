import java.util.ArrayList;
import java.util.HashMap;
import java.util.concurrent.TimeUnit;
import java.util.logging.Level;
import java.util.logging.Logger;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author serdar
 */
public class Tir implements Runnable{
    
    int ID;
    public static int sayac = 0;
    String plaka;
    
    public Tir(int ID){
        this.ID = ID;
    }
    
    int maxDepo = 66;
    int kalanDepo = 66;
    
    int donmus = 0;
    int taze = 0;
    int ETA=0;
    int hizCarpani = 1;
    
    
    Thread t;
    
    
    
    ArrayList<Sehir> rota = new ArrayList<Sehir>();
    HashMap<Sehir, Integer> tazeBolumleri = new HashMap<Sehir, Integer>();
    HashMap<Sehir, Integer> donmusBolumleri = new HashMap<Sehir, Integer>();
    
    
    
    public boolean donmusEkle(int eklenenDonmusSayisi, Sehir sehir){
        kalanDepoHesapla();
        if(eklenenDonmusSayisi > 0){
            if(taze == 0){
                if(eklenenDonmusSayisi <= kalanDepo){
                    donmus += eklenenDonmusSayisi;
                    kalanDepoHesapla();
                    
                    if(rotadaAra(sehir)){
                        donmusBolumleri.put(sehir, donmusBolumleri.get(sehir) + eklenenDonmusSayisi);
                    }else{
                        donmusBolumleri.put(sehir, eklenenDonmusSayisi);
                        tazeBolumleri.put(sehir, 0);
                    }
                    rotayaEkle(sehir);
                    return true;
                }else{
                    return false;
                }
            }else{
                maxDepo = 64;
                if(eklenenDonmusSayisi <= kalanDepo){
                    donmus += eklenenDonmusSayisi;
                    kalanDepoHesapla();
                    
                    if(rotadaAra(sehir)){
                        donmusBolumleri.put(sehir, donmusBolumleri.get(sehir) + eklenenDonmusSayisi);
                    }else{
                        donmusBolumleri.put(sehir, eklenenDonmusSayisi);
                        tazeBolumleri.put(sehir, 0);
                    }
                    
                    rotayaEkle(sehir);
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }     
    }
    
    public boolean tazeEkle(int eklenenTazeSayisi, Sehir sehir){  
        kalanDepoHesapla();
        if(eklenenTazeSayisi > 0){
            if(donmus == 0){
                if(eklenenTazeSayisi <= kalanDepo){
                    taze += eklenenTazeSayisi;
                    kalanDepo = kalanDepoHesapla();
                    if(rotadaAra(sehir)){
                        tazeBolumleri.put(sehir, tazeBolumleri.get(sehir) + eklenenTazeSayisi);
                    }else{
                        tazeBolumleri.put(sehir, eklenenTazeSayisi);
                        donmusBolumleri.put(sehir, 0);
                    }
                    rotayaEkle(sehir);
                    
                    return true;
                }else{
                    return false;
                }
            }else{
                maxDepo = 64;
                if(eklenenTazeSayisi <= kalanDepo){
                    taze += eklenenTazeSayisi;
                    kalanDepo = kalanDepoHesapla();
                    if(rotadaAra(sehir)){
                        tazeBolumleri.put(sehir, tazeBolumleri.get(sehir) + eklenenTazeSayisi);
                    }else{
                        tazeBolumleri.put(sehir, eklenenTazeSayisi);
                        donmusBolumleri.put(sehir, 0);
                    }
                    rotayaEkle(sehir);
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }  
    }
    
    

    public int getETA() {
        return ETA;
    }

    public void setETA(int ETA) {
        this.ETA = ETA;
    }

    public HashMap<Sehir, Integer> getTazeBolumleri() {
        return tazeBolumleri;
    }

    public void setTazeBolumleri(HashMap<Sehir, Integer> tazeBolumleri) {
        this.tazeBolumleri = tazeBolumleri;
    }

    public HashMap<Sehir, Integer> getDonmusBolumleri() {
        return donmusBolumleri;
    }

    public void setDonmusBolumleri(HashMap<Sehir, Integer> donmusBolumleri) {
        this.donmusBolumleri = donmusBolumleri;
    }
    
    public int kalanDepoHesapla(){
        kalanDepo = maxDepo - (donmus + taze);
        return kalanDepo;
    }
    
    public void maxDepoKontrolEt(int eklenecekTur){
        //taze eklenecekse
        if(eklenecekTur == 0){
            if(donmus == 0){
                return;
            }else{
                maxDepo = 64;
                
            }
        }else if(eklenecekTur == 1){
            //donmus eklenecekse
            if(taze == 0){
                return;
            }else{
               maxDepo = 64; 
            }
        }
        kalanDepoHesapla();
        
    }
    
    public void ETAIlerlet(){
        if(ETA > 0){
           this.ETA = this.ETA - hizCarpani; 
        }
        
    }
    
    public void run() {
      while(ETA > 0){
          ETAIlerlet();
          try {
              TimeUnit.SECONDS.sleep(1);
          } catch (InterruptedException ex) {
              Logger.getLogger(Tir.class.getName()).log(Level.SEVERE, null, ex);
          }
      }
    }

    public int getID() {
        return ID;
    }

    public void setID(int ID) {
        this.ID = ID;
    }

    public int getHizCarpani() {
        return hizCarpani;
    }

    public void setHizCarpani(int hizCarpani) {
        this.hizCarpani = hizCarpani;
    }

    
    public void start () {
      if (t == null) {
         t = new Thread (this);
         t.start ();
      }
   }
    
    public void gecikmeEkle(int gecikmeSuresi){
        this.ETA = ETA + gecikmeSuresi;
    }
    

    
    
    
    
    
    
    
    
    
    public int getMaxDepo() {
        return maxDepo;
    }

    public void setMaxDepo(int maxDepo) {
        this.maxDepo = maxDepo;
    }

    public int getKalanDepo() {
        return kalanDepo;
    }

    public void setKalanDepo(int kalanDepo) {
        this.kalanDepo = kalanDepo;
    }

    public int getDonmus() {
        return donmus;
    }

    public void setDonmus(int donmus) {
        this.donmus = donmus;
    }

    public int getTaze() {
        return taze;
    }

    public void setTaze(int taze) {
        this.taze = taze;
    }
    
    public void rotayaEkle(Sehir sehir){
        boolean rotadaYok = true;
        for(Sehir arananSehir : rota){
            if(arananSehir == sehir){
                rotadaYok = false;
            }
        }
        if(rotadaYok){
            rota.add(sehir);
        }
    }
    
    public boolean rotadaAra(Sehir sehir){
        for(Sehir arananSehir : rota){
            if(arananSehir == sehir){
                return true;
            }
        }
        return false;
    }

    public ArrayList<Sehir> getRota() {
        return rota;
    }

    public void setRota(ArrayList<Sehir> rota) {
        this.rota = rota;
    }
    
    
    
}

