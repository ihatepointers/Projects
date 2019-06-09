/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author serdar
 */
public class Sehir {
    
    int tazeSiparis = 0;
    int donmusSiparis = 0;
    int sabitTaze;
    int sabitDonmus;
    int mesafe = 0;
    String sehirIsmi = "";
    String rotaIsmi = "";
    int onemDerecesi = -1;
    
    public Sehir(String sehirIsmi, int mesafe, String rotaIsmi, int tazeSiparis, int donmusSiparis){
        this.sehirIsmi = sehirIsmi;
        this.mesafe = mesafe;
        this.rotaIsmi = rotaIsmi;
        this.tazeSiparis = tazeSiparis;
        this.donmusSiparis = donmusSiparis;
        this.sabitTaze = tazeSiparis;
        this.sabitDonmus = donmusSiparis;
        onemDerecesiHesapla();
    }
    
    public Sehir(String sehirIsmi, int tazeSiparis, int donmusSiparis){
        this.sehirIsmi = sehirIsmi;
        this.tazeSiparis = tazeSiparis;
        this.donmusSiparis = donmusSiparis;
        this.sabitTaze = tazeSiparis;
        this.sabitDonmus = donmusSiparis;
    }

    public int getOnemDerecesi() {
        return onemDerecesi;
    }

    public void setOnemDerecesi(int onemDerecesi) {
        this.onemDerecesi = onemDerecesi;
    }
    
    public void onemDerecesiHesapla(){
        if(sehirIsmi.toLowerCase().contains("bim") || sehirIsmi.toLowerCase().contains("a101")){
            onemDerecesi = 2;
        }else if(tazeSiparis != 0){
            onemDerecesi = 1;
        }else{
            onemDerecesi = 0;
        }
    }

    public String getRotaIsmi() {
        return rotaIsmi;
    }

    public void setRotaIsmi(String rotaIsmi) {
        this.rotaIsmi = rotaIsmi;
    }

    public int getTazeSiparis() {
        return tazeSiparis;
    }

    public void setTazeSiparis(int tazeSiparis) {
        this.tazeSiparis = tazeSiparis;
    }

    public int getDonmusSiparis() {
        return donmusSiparis;
    }

    public void setDonmusSiparis(int donmusSiparis) {
        this.donmusSiparis = donmusSiparis;
    }

    public int getMesafe() {
        return mesafe;
    }

    public void setMesafe(int mesafe) {
        this.mesafe = mesafe;
    }

    public String getSehirIsmi() {
        return sehirIsmi;
    }

    public void setSehirIsmi(String sehirIsmi) {
        this.sehirIsmi = sehirIsmi;
    }
    
    public void siparisleriYenile(){
        tazeSiparis = sabitTaze;
        donmusSiparis = sabitDonmus;
        
    }

    public int getSabitTaze() {
        return sabitTaze;
    }

    public void setSabitTaze(int sabitTaze) {
        this.sabitTaze = sabitTaze;
    }

    public int getSabitDonmus() {
        return sabitDonmus;
    }

    public void setSabitDonmus(int sabitDonmus) {
        this.sabitDonmus = sabitDonmus;
    }
    
    
    
    
    
    
}
