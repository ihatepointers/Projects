
import java.util.ArrayList;
import java.util.HashMap;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author serdar
 */
public class Rota {
    int toplamTaze=0;
    int toplamDonmus=0;
    int toplamYuk=0;
    int gerekenTirSayisi=0;
    String rotaIsmi;
    ArrayList<Sehir> rota;
    ArrayList<Tir> tirlar;
    
    public Rota(ArrayList<Sehir> rotaSirasi, String rotaIsmi){
        this.rota = rotaSirasi;
        this.rotaIsmi = rotaIsmi;
        toplamYukleriHesapla();
        

        
    }
    
    public void toplamYukleriHesapla(){
        toplamTaze = 0;
        toplamDonmus = 0;
        toplamYuk = 0;
        gerekenTirSayisi = 0;
        for(int i=0; i<rota.size() ; i++){
            toplamTaze += rota.get(i).getTazeSiparis();
            toplamDonmus += rota.get(i).getDonmusSiparis();
        }
        toplamYuk = toplamTaze + toplamDonmus;
        gerekenTirSayisiHesapla();
        rotaSirasiHesapla();
    }

    
    public void gerekenTirSayisiHesapla(){
        gerekenTirSayisi = (toplamYuk / 66);
        if((toplamYuk % 66) != 0){
            gerekenTirSayisi++;
        }
    }
    
    public void rotaSirasiHesapla(){
        ArrayList<Sehir> tmpRota = new ArrayList<Sehir>();
        int i;
        int j;
        for(i=2 ; i>-1; i--){
            for(j=0 ; j<rota.size() ; j++){
                if(rota.get(j).getOnemDerecesi() == i){
                    tmpRota.add(rota.get(j));
                }
            }
        }
        rota = tmpRota;
    }
    
    public void tirlaraYuklemeYap(ArrayList<Tir> tirlar){
        int rotaIndexi = 0;
        for(int i=0 ; i< gerekenTirSayisi ; i++){
            Tir tir = new Tir(Tir.sayac);
            Tir.sayac++;
            while( (tir.getKalanDepo() > 0) && (rotaIndexi < rota.size()) ){
                tiraTazeYukle(tir, rota.get(rotaIndexi).getTazeSiparis(), rota.get(rotaIndexi));
                tiraDonmusYukle(tir, rota.get(rotaIndexi).getDonmusSiparis(), rota.get(rotaIndexi));
                if( (rota.get(rotaIndexi).getDonmusSiparis() == 0) && (rota.get(rotaIndexi).getTazeSiparis() == 0) ){
                    rotaIndexi++;
                }
            }
            tirlar.add(tir);
        }
    }
    
    public void tiraDonmusYukle(Tir tir, int donmusSiparisi, Sehir hedefSehir){
        
        //int alinabilecekDonmusMiktari = tir.getMaxDepo() - tir.getKalanDepo();
        
        //0-taze 1-donmus
        if((tir.getKalanDepo() > 0) && (donmusSiparisi > 0)){
            tir.maxDepoKontrolEt(1);
            if(donmusSiparisi <= tir.getKalanDepo()){
                tir.donmusEkle(donmusSiparisi, hedefSehir);
                hedefSehir.setDonmusSiparis(0);
                tir.setETA(hedefSehir.getMesafe());
            }else{
                int oncekiDonmus = donmusSiparisi;
                int eklenecekDonmus = tir.getKalanDepo();
                int sonrakiDonmus = oncekiDonmus - eklenecekDonmus;
                hedefSehir.setDonmusSiparis(sonrakiDonmus);
                tir.donmusEkle(eklenecekDonmus, hedefSehir);
                tir.setETA(hedefSehir.getMesafe());
            } 
        }
        
    }
    
    public void tiraTazeYukle(Tir tir, int tazeSiparisi, Sehir hedefSehir){
        
        //int alinabilecekDonmusMiktari = tir.getMaxDepo() - tir.getKalanDepo();
        
        //0-taze 1-donmus
        if(tir.getKalanDepo() > 2){
            tir.maxDepoKontrolEt(0);
            if(tazeSiparisi <= tir.getKalanDepo()){
                tir.tazeEkle(tazeSiparisi, hedefSehir);
                hedefSehir.setTazeSiparis(0);
                tir.setETA(hedefSehir.getMesafe());
            }else{
                int oncekiTaze = tazeSiparisi;
                int eklenecekTaze = tir.getKalanDepo();
                int sonrakiTaze = oncekiTaze - eklenecekTaze;
                hedefSehir.setTazeSiparis(sonrakiTaze);
                tir.tazeEkle(eklenecekTaze, hedefSehir);
                tir.setETA(hedefSehir.getMesafe());
            }  
        }
        
    }
    
    
    
    
    

    public int getToplamTaze() {
        return toplamTaze;
    }

    public void setToplamTaze(int toplamTaze) {
        this.toplamTaze = toplamTaze;
    }

    public int getToplamDonmus() {
        return toplamDonmus;
    }

    public void setToplamDonmus(int toplamDonmus) {
        this.toplamDonmus = toplamDonmus;
    }

    public int getToplamYuk() {
        return toplamYuk;
    }

    public void setToplamYuk(int toplamYuk) {
        this.toplamYuk = toplamYuk;
    }

    public int getGerekenTirSayisi() {
        return gerekenTirSayisi;
    }

    public void setGerekenTirSayisi(int gerekenTirSayisi) {
        this.gerekenTirSayisi = gerekenTirSayisi;
    }

    public String getRotaIsmi() {
        return rotaIsmi;
    }

    public void setRotaIsmi(String rotaIsmi) {
        this.rotaIsmi = rotaIsmi;
    }

    public ArrayList<Sehir> getRota() {
        return rota;
    }

    public void setRota(ArrayList<Sehir> rota) {
        this.rota = rota;
    }
    
    public void sehirSiraDegistir(Sehir sehir1, Sehir sehir2){
        int i=0;
        int index1 = 0;
        int index2 = 0;
        for(i=0 ; i<rota.size() ; i++){
            if(rota.get(i).equals(sehir1)){
                index1 = i;
            }
            if(rota.get(i).equals(sehir2)){
                index2 = i;
            }
        }
        rota.set(index1, sehir2);
        rota.set(index2, sehir1);
    }
    
}
