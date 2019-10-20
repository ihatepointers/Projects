using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class HexagonScript : MonoBehaviour
{

    public int positionX;
    public int positionY;
    void Start()
    {
        
    }

    void Update()
    {
        
    }

    public void setPositions(int x, int y)
    {
        positionX = x;
        positionY = y;
    }

    public int getPositionX()
    {
        return positionX;
    }

    public int getPositionY()
    {
        return positionY;
    }

    

}
