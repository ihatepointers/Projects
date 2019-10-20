using System;
using System.Collections;
using System.Collections.Generic;
using TMPro;
using UnityEngine;
using UnityEngine.UI;

public class GameManagerScript : MonoBehaviour
{
    private int score = 0;
    public TextMeshProUGUI ScoreText;
    public List<GameObject> bombs;
    public GameObject movementManager;
    public GameObject touchScreenManager;
    public GameObject endScreen;

    void Start()
    {
        ScoreText = GameObject.Find("ScoreText").GetComponent<TextMeshProUGUI>();
        ScoreText.text = score.ToString();
        movementManager = GameObject.Find("MovementManager");
        touchScreenManager = GameObject.Find("TouchScreenManager");
    }

    void Update()
    {
        
    }

    public void addScore(int addedAmount)
    {
        this.score += addedAmount;
        ScoreText.text = score.ToString();
    }

    public int getScore()
    {
        return this.score;
    }

    public void addBomb(GameObject bombHexagon)
    {
        bombs.Add(bombHexagon);
        bombHexagon.GetComponent<BombScript>().setBomb();
    }


    //Called after every succesfull rotation to keep the bombs ticking
    public void tick()
    {
        for(int i = 0; i < bombs.Count; i++)
        {
            try
            {
                if (bombs[i].GetComponent<BombScript>().tick() == 0)
                {
                    bombs[i].GetComponent<BombScript>().explode();
                    gameOver();
                }
            }
            catch (Exception e)
            {
                Debug.Log(e.StackTrace);
                bombs.Remove(bombs[i]);
            }
            
        }
    }

    public void gameOver()
    {
        //stop movement
        movementManager.SetActive(false);
        touchScreenManager.SetActive(false);

        //Put Game Over text over black screen
        this.gameObject.transform.GetChild(0).transform.GetChild(0).gameObject.SetActive(true);
        this.gameObject.transform.GetChild(0).transform.GetChild(1).gameObject.SetActive(true);

        Debug.Log("GameOver...");
    }

    public void noMoreMovesLeft()
    {
        //stop movement
        movementManager.SetActive(false);
        touchScreenManager.SetActive(false);

        //Inform the player that no more possible moves are left
        this.gameObject.transform.GetChild(0).transform.GetChild(2).gameObject.SetActive(true);

        Debug.Log("GameOver...");
    }
}
