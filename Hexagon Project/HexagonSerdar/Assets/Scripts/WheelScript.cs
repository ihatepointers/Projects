using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class WheelScript : MonoBehaviour
{

    public GameObject hexagon0;
    public GameObject hexagon1;
    public GameObject hexagon2;

    int x1, y1, x2, y2, x3, y3;

    public bool popable = false;

    public int type;


    public GameObject movementManager;

    void Start()
    {
        movementManager = GameObject.Find("MovementManager");
    }

    void Update()
    {

    }


    public void selectWheel()
    {
        
    }

    //set this wheel's coordinates in hexagonMatrix
    public void setCoordinates(int x1, int y1, int x2, int y2, int x3, int y3)
    {
        this.x1 = x1;
        this.y1 = y1;

        this.x2 = x2;
        this.y2 = y2;

        this.x3 = x3;
        this.y3 = y3;
    }

    public int[] getCoordinates()
    {
        int[] coordinateArray = { x1, y1, x2, y2, x3, y3 };
        return coordinateArray;
    }

    //Set the actual hexagon objects of the wheel
    public void setHexagons(GameObject hex0, GameObject hex1, GameObject hex2)
    {
        
        hexagon0 = hex0;
        hexagon1 = hex1;
        hexagon2 = hex2;

        if((hexagon0.transform.name == hexagon1.transform.name) && (hexagon1.transform.name == hexagon2.transform.name))
        {
            popable = true;
        }
        else
        {
            popable = false;
        }

    }

    public void resetPopable()
    {
        if ((hexagon0.transform.name == hexagon1.transform.name) && (hexagon1.transform.name == hexagon2.transform.name))
        {
            popable = true;
        }
        else
        {
            popable = false;
        }
    }

    public GameObject[] getHexagons()
    {
        GameObject[] hexagonArray = { hexagon0, hexagon1, hexagon2 };
        return hexagonArray;
    }

    public string getInfo()
    {
        string debugText = "Wheel: " + this.transform.name +" - Hexagons: " + hexagon0.transform.name + " , " + hexagon1.transform.name + " , " + hexagon2.transform.name;

        return debugText;
    }

    public bool getPopable()
    {
        return popable;
    }

    public void setType(int type)
    {
        this.type = type;
    }

    public int getType()
    {
        return type;
    }

}
