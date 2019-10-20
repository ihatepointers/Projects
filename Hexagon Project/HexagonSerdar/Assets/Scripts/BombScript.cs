using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using TMPro;

public class BombScript : MonoBehaviour
{

    public TextMeshProUGUI bombText;
    private int remainingRounds = 8;
    AudioSource audioSource;
    public AudioClip hissClip;
    public AudioClip explosionClip;

    void Start()
    {
        bombText.text = remainingRounds.ToString();
    }

    void Update()
    {
        
    }

    public int tick()
    {
        //progress the timer and update the text
        remainingRounds--;
        bombText.text = remainingRounds.ToString();
        return remainingRounds;
    }

    public void setBomb()
    {
        //Start the bomb with necessary objects
        audioSource = GetComponent<AudioSource>();
        bombText = this.transform.GetChild(1).transform.GetChild(0).transform.GetChild(0).GetComponent<TextMeshProUGUI>();
        this.remainingRounds = 8;
        audioSource.PlayOneShot(hissClip, 1.0F);
        bombText.text = remainingRounds.ToString();

    }

    public void explode()
    {
        audioSource.PlayOneShot(explosionClip, 1.0F);

    }
}
