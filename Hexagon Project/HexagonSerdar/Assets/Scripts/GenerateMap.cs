using System.Collections;
using System.Collections.Generic;
using System.Linq;
using UnityEngine;



public class GenerateMap : MonoBehaviour
{

    
    public GameObject hexagonPrefab;
    public GameObject wheelPrefab;
    public GameObject wheelPrefab_1;
    public GameObject wheelPrefab_2;
    public GameObject wheels;
    public GameObject movementManager;

    private GameObject[][] hexagonMatrix;
    private Vector3[][] positionMatrix;
    private List<GameObject> wheelList = new List<GameObject>();


    // Size of the map
    public int map_width = 8;
    public int map_height = 9;

    //offset values to place hexagons correctly
    float xOffset = 0.75f;
    float yOffset = 0.866f;

    public int colorAmount = 5;

    //Hex values in colorArray  :      Red       Green      Blue      Yellow      Orange    Magenta     Cyan          "Indigo"     "Pink"    "Black"
    private string[] colorArray = { "#ff0000", "#00FF00", "#0000ff", "#ffff00", "#ffa500", "#ff00ff", "#00ffff" }; //"#4B0082", "#FF69B4", "#000000"};

    //The randomly picked colors will be added to this array to be used later
    private string[] usableColorArray;




    void Start()
    {
        usableColorArray = new string[colorAmount];

        selectRandomColors(colorAmount, colorArray, usableColorArray);
        
        createEmptyHexagonMatrix(map_height, map_width);

        generateHexagons(map_height, map_width);

        generateWheels(map_height, map_width, hexagonMatrix);

        changePopables(map_height, map_width, hexagonMatrix);

        movementManager.GetComponent<MovementManagerScript>().setHexagonMatrix(hexagonMatrix);

        movementManager.GetComponent<MovementManagerScript>().setPositionMatrix(positionMatrix);

        movementManager.GetComponent<MovementManagerScript>().setWheelList(wheelList);

        movementManager.GetComponent<MovementManagerScript>().setUsableColorArray(usableColorArray);


        //printHexagonMatrix(map_height, map_width, hexagonMatrix);

        //printWheels(wheelList);

    }

    void Update()
    {
        
    }

    public int getHeight()
    {
        return this.map_height;
    }

    public int getWidth()
    {
        return this.map_width;
    }

    private void selectRandomColors(int colorAmount, string[] colorArray, string[] usableColorArray)
    {

        System.Random rnd = new System.Random();
        

        //shuffle the color array 
        string[] randomColorArray = colorArray.OrderBy(x => rnd.Next()).ToArray();

        //pick the required amount of colors
        for (int i = 0; i < colorAmount; i++)
        {
            usableColorArray[i] = randomColorArray[i];
        }

        



    }


    //Create a matrix with necessary size to hold information of hexagons
    private void createEmptyHexagonMatrix(int height, int width)
    {
        hexagonMatrix = new GameObject[height][];
        positionMatrix = new Vector3[height][];
        for (int i = 0; i < height; i++)
        {
            hexagonMatrix[i] = new GameObject[width];
            positionMatrix[i] = new Vector3[width];
        }
    }



    //Generating the hexagons with the offset values to achive the honeycomb look
    void generateHexagons(int height, int width)
    {

        float xPos = 0;
        float yPos = 0;

        for (int x = 0; x < height; x++)
        {
            for (int y = 0; y < width; y++)
            {
                xPos += xOffset;
                GameObject hexagonObject = (GameObject)Instantiate(hexagonPrefab, new Vector3(xPos, 0, yPos+ ((y % 2) * (yOffset / 2)) ), Quaternion.identity);
                //hexagonObject.name = "hexagon_" + x + "_" + y;
                hexagonObject.transform.SetParent(this.transform);

                Color randomColor = new Color();
                int randomColorIndex = Random.Range(0, usableColorArray.Length);
                ColorUtility.TryParseHtmlString(usableColorArray[randomColorIndex], out randomColor);

                hexagonObject.name = "hexagon_" + usableColorArray[randomColorIndex];

                hexagonObject.transform.GetChild(0).GetComponent<Renderer>().material.SetColor("_Color", randomColor);
                hexagonObject.GetComponent<HexagonScript>().setPositions(x, y);
                hexagonMatrix[x][y] = hexagonObject;
                positionMatrix[x][y] = new Vector3(hexagonObject.transform.position.x, 0, hexagonObject.transform.position.z);
            }
            xPos = 0;
            yPos += yOffset;
        }

    }

    //Placing wheels at the center of hexagon trios
    void generateWheels(int height, int width, GameObject[][] hexagonMatrix)
    {

        int wheelCounter = 0;

        for (int x = 0; x < height; x++)
        {
            for (int y = 0; y < width; y++)
            {

                //up-diagonal check
                if ((x + 1 < height) && (y + 1 < width))
                {
                    if (y % 2 == 0)
                    {
                        Vector3 trioCenter = (hexagonMatrix[x][y].transform.position + hexagonMatrix[x][y+1].transform.position + hexagonMatrix[x+1][y].transform.position) / 3;
                        GameObject wheelObject = (GameObject)Instantiate(wheelPrefab_1, trioCenter, Quaternion.identity);
                        wheelObject.name = "Wheel" + wheelCounter;
                        wheelCounter++;
                        wheelObject.GetComponent<WheelScript>().setHexagons(hexagonMatrix[x][y], hexagonMatrix[x+1][y], hexagonMatrix[x][y+1]);
                        wheelObject.transform.SetParent(wheels.transform);
                        wheelObject.GetComponent<WheelScript>().setType(0);
                        wheelObject.GetComponent<WheelScript>().setCoordinates(x, y, x+1, y, x, y+1);
                        wheelObject.transform.GetChild(0).gameObject.SetActive(false);
                        wheelList.Add(wheelObject);
                    }
                    else
                    {
                        Vector3 trioCenter = (hexagonMatrix[x][y].transform.position + hexagonMatrix[x+1][y].transform.position + hexagonMatrix[x+1][y+1].transform.position) / 3;
                        GameObject wheelObject = (GameObject)Instantiate(wheelPrefab_1, trioCenter, Quaternion.identity);
                        wheelObject.name = "Wheel" + wheelCounter;
                        wheelCounter++;
                        wheelObject.GetComponent<WheelScript>().setHexagons(hexagonMatrix[x][y], hexagonMatrix[x+1][y], hexagonMatrix[x+1][y+1]);
                        wheelObject.transform.SetParent(wheels.transform);
                        wheelObject.GetComponent<WheelScript>().setType(1);
                        wheelObject.GetComponent<WheelScript>().setCoordinates(x, y, x+1, y, x+1, y+1);
                        wheelObject.transform.GetChild(0).gameObject.SetActive(false);
                        wheelList.Add(wheelObject);

                    }
                }

                
                //diagonal-diagonal check
                if ((x + 1 < height) && (y - 1 >= 0))
                {
                    if (y % 2 == 0)
                    {
                        Vector3 trioCenter = (hexagonMatrix[x][y].transform.position + hexagonMatrix[x][y-1].transform.position + hexagonMatrix[x+1][y].transform.position) / 3;
                        GameObject wheelObject = (GameObject)Instantiate(wheelPrefab_2, trioCenter, Quaternion.identity);
                        wheelObject.name = "Wheel" + wheelCounter;
                        wheelCounter++;
                        wheelObject.GetComponent<WheelScript>().setHexagons(hexagonMatrix[x][y], hexagonMatrix[x][y-1], hexagonMatrix[x+1][y]);
                        wheelObject.transform.SetParent(wheels.transform);
                        wheelObject.GetComponent<WheelScript>().setType(2);
                        wheelObject.GetComponent<WheelScript>().setCoordinates(x, y, x, y-1, x+1, y);
                        wheelObject.transform.GetChild(0).gameObject.SetActive(false);
                        wheelList.Add(wheelObject);
                    }
                    else
                    {
                        Vector3 trioCenter = (hexagonMatrix[x][y].transform.position + hexagonMatrix[x+1][y-1].transform.position + hexagonMatrix[x+1][y].transform.position) / 3;
                        GameObject wheelObject = (GameObject)Instantiate(wheelPrefab_2, trioCenter, Quaternion.identity);
                        wheelObject.name = "Wheel" + wheelCounter;
                        wheelCounter++;
                        wheelObject.GetComponent<WheelScript>().setHexagons(hexagonMatrix[x][y], hexagonMatrix[x+1][y-1], hexagonMatrix[x+1][y]);
                        wheelObject.transform.SetParent(wheels.transform);
                        wheelObject.GetComponent<WheelScript>().setType(3);
                        wheelObject.GetComponent<WheelScript>().setCoordinates(x, y, x+1, y-1, x+1, y);
                        wheelObject.transform.GetChild(0).gameObject.SetActive(false);
                        wheelList.Add(wheelObject);
                    }
                }
                
            }
        }
    }

    //if the game is generated with popable wheels, change one of the hexagon's color randomly
    void changePopables(int height, int width, GameObject[][] hexagonMatrix)
    {
        GameObject hexagonObject;
        for(int i = 0; i < wheelList.Count; i++)
        {
            while (wheelList[i].GetComponent<WheelScript>().getPopable())
            {
                hexagonObject = wheelList[i].GetComponent<WheelScript>().getHexagons()[Random.Range(0,2)];
                Color randomColor = new Color();
                int randomColorIndex = Random.Range(0, usableColorArray.Length);
                ColorUtility.TryParseHtmlString(usableColorArray[randomColorIndex], out randomColor);

                hexagonObject.name = "hexagon_" + usableColorArray[randomColorIndex];

                hexagonObject.transform.GetChild(0).GetComponent<Renderer>().material.SetColor("_Color", randomColor);

                wheelList[i].GetComponent<WheelScript>().resetPopable();
            }
        }
    }

    //for debug purposes
    void printHexagonMatrix(int height, int width, GameObject[][] hexagonMatrix)
    {
        for(int i = 0; i < height; i++)
        {
            for (int j = 0; j < width; j++)
            {
                string debugText = i.ToString() + ", " + j.ToString() + " - " + hexagonMatrix[i][j].name;

                Debug.Log(debugText);
            }
        }
    }

    //for debug purposes
    void printWheels(List<GameObject> wheelList)
    {
        foreach (var wheel in wheelList)
        {
            string debugText = wheel.GetComponent<WheelScript>().getInfo();
            Debug.Log(debugText);

        }

    }

}
