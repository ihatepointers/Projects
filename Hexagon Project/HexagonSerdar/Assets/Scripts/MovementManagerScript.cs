using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class MovementManagerScript : MonoBehaviour
{

    private GameObject[][] hexagonMatrix;
    private Vector3[][] positionMatrix;
    private List<GameObject> wheelList = new List<GameObject>();
    private string[] usableColorArray;
    private int finishedColumnCounter;
    public bool rotating = false;
    public bool wheelLocked = false;
    public GameObject hexagonPrefab;
    public GameObject gameManager;
    public GameObject bombPrefab;
    public GameObject selectedWheel = null;
    public float selectedWheelPosX = 0;
    public GameObject gridCenter;
    public int bombCount = 1;
    public AudioClip popClip;
    AudioSource audioSource;

    void Start()
    {
        audioSource = GetComponent<AudioSource>();
        gridCenter = GameObject.Find("GridCenter");
        gameManager = GameObject.Find("GameManager");
        recalculateWheels();
    }

    void Update()
    {
        
    }

    public void setHexagonMatrix(GameObject[][] hexagonMatrix)
    {
        this.hexagonMatrix = hexagonMatrix;
    }

    public void setPositionMatrix(Vector3[][] positionMatrix)
    {
        this.positionMatrix = positionMatrix;
    }

    public void setWheelList(List<GameObject> wheelList)
    {
        this.wheelList = wheelList;
    }

    public void setUsableColorArray(string[] usableColorArray)
    {
        this.usableColorArray = usableColorArray;
    }

    public float getSelectedWheelPosX()
    {
        return selectedWheelPosX;
    }

    public void changeSelectedWheel(GameObject wheel, float posX)
    {
        if(selectedWheel != null)
        {
            selectedWheel.transform.GetChild(0).gameObject.SetActive(false);
        }
        
        selectedWheel = wheel;
        selectedWheel.transform.GetChild(0).gameObject.SetActive(true);
        selectedWheelPosX = posX;
    }

    

    public void rotateWheel(string direction)
    {
        GameObject wheel = selectedWheel;
        if (!rotating)
        {
            GameObject[] hexagonArray = wheel.GetComponent<WheelScript>().getHexagons();
            Transform[] tmpTransforms = { hexagonArray[0].transform, hexagonArray[1].transform, hexagonArray[2].transform };
            GameObject tmpObject = hexagonArray[0];


            //this bool locks the game until rotations are complete
            rotating = true;



            hexagonArray[0].transform.SetParent(wheel.transform);
            hexagonArray[1].transform.SetParent(wheel.transform);
            hexagonArray[2].transform.SetParent(wheel.transform);


            StartCoroutine(RotateHexagons(wheel, direction));


            
        }
    }

    IEnumerator RotateHexagons(GameObject wheel, string direction)
    {
        int rotationCount = 0;
        bool popped = false;
        while (rotationCount < 3)
        {
            Quaternion start = wheel.transform.rotation;
            Quaternion destination = this.transform.rotation;
            if (direction == "clockwise")
            {
                destination = start * Quaternion.Euler(new Vector3(0f, 120f, 0f));
            }
            else
            {
                destination = start * Quaternion.Euler(new Vector3(0f, -120f, 0f));
            }
            
            float startTime = Time.time;
            float timeToRotate = 0.3f;
            float percentComplete = 0.0f;
            while (percentComplete <= 1.0f)
            {
                percentComplete = (Time.time - startTime) / timeToRotate;
                wheel.transform.rotation = Quaternion.Slerp(start, destination, percentComplete);
                yield return null;
            }

            int[] coordinateArray = wheel.GetComponent<WheelScript>().getCoordinates();
            int x1, y1, x2, y2, x3, y3;
            x1 = coordinateArray[0];
            y1 = coordinateArray[1];
            x2 = coordinateArray[2];
            y2 = coordinateArray[3];
            x3 = coordinateArray[4];
            y3 = coordinateArray[5];

            
            if(direction == "clockwise")
            {
                GameObject tmp = hexagonMatrix[x3][y3];
                hexagonMatrix[x3][y3] = hexagonMatrix[x2][y2];
                hexagonMatrix[x2][y2] = hexagonMatrix[x1][y1];
                hexagonMatrix[x1][y1] = tmp;
            }
            else
            {
                GameObject tmp = hexagonMatrix[x2][y2];
                hexagonMatrix[x2][y2] = hexagonMatrix[x3][y3];
                hexagonMatrix[x3][y3] = hexagonMatrix[x1][y1];
                hexagonMatrix[x1][y1] = tmp;
            }
            
            recalculateWheels();

            popped = checkForPops();
            
            if (popped)
            {
                rotationCount = 3;
            }
            else
            {
                rotationCount++;
            }

            


        }

        if (popped)
        {
            audioSource.PlayOneShot(popClip, 1.0F);
            //if there are popped hexagons, let upper hexagons fall down
            bringDownColumns(gridCenter.GetComponent<GenerateMap>().getHeight(), gridCenter.GetComponent<GenerateMap>().getWidth());

            //Progress the bomb timers
            gameManager.GetComponent<GameManagerScript>().tick();
            popped = false;
        }
        else
        {
            rotating = false;
        }


        //reset parents
        while (wheel.transform.childCount > 1)
        {
            wheel.transform.GetChild(1).transform.SetParent(gridCenter.transform);
        }

        recalculateWheels();




    }



    //Recalcute the hexagons of the wheels after changes in the hexagonMatrix
    public void recalculateWheels()
    {

        for (int i = 0; i < wheelList.Count; i++)
        {
            WheelScript ws = wheelList[i].GetComponent<WheelScript>();
            int type = ws.getType();
            int[] coordinates = ws.getCoordinates();

            ws.setHexagons(hexagonMatrix[coordinates[0]][coordinates[1]], hexagonMatrix[coordinates[2]][coordinates[3]], hexagonMatrix[coordinates[4]][coordinates[5]]);


        }
    }

    //Check if newly calculated wheels have hexagons of same color
    public bool checkForPops()
    {

        bool popped = false;
        for (int i = 0; i < wheelList.Count; i++)
        {
            if (wheelList[i].GetComponent<WheelScript>().getPopable())
            {
                GameObject[] hexagons = wheelList[i].GetComponent<WheelScript>().getHexagons();
                int[] coordinateArray = wheelList[i].GetComponent<WheelScript>().getCoordinates();
                int x1, y1, x2, y2, x3, y3;
                x1 = coordinateArray[0];
                y1 = coordinateArray[1];
                x2 = coordinateArray[2];
                y2 = coordinateArray[3];
                x3 = coordinateArray[4];
                y3 = coordinateArray[5];

                for (int j = 0; j < 3; j++)
                {
                    Destroy(hexagons[j].gameObject);
                }

                



                hexagonMatrix[x1][y1] = hexagonMatrix[x2][y2] = hexagonMatrix[x3][y3] = null;
                popped = true;

            }
        }
        return popped;
    }

    //Let the upper hexagons fall down
    void bringDownColumns(int height, int width)
    {
        int i = 0;
        int j = 0;
        int changedColumnAmount = 0;

        finishedColumnCounter = 0;

        List<GameObject> newHexagons = new List<GameObject>();


        //every column is calculated seperately to be used in coroutines simultaneously
        for (j = 0; j < width; j++)
        {
            for (i = 0; i < height; i++)
            {
                while(hexagonMatrix[i][j] == null)
                {
                    newHexagons.Add(bringDownSingleColumn(height, width, i, j));
                }
            }
        }

        //popped coordinate tuples are stored in a bottom-to-top manner 
        var tupleList = new List<(int, int)>();
        for (j = 0; j < width; j++)
        {
            for (i = 0; i < height; i++)
            {
                if (hexagonMatrix[i][j].transform.position != positionMatrix[i][j])
                {
                    tupleList.Add((i, j));
                }
            }
        }

        //Start coroutines of every column seperately
        var tmpList = new List<(int, int)>();
        int currentColumn = tupleList[0].Item2;
        for (int k = 0; k < tupleList.Count; k++)
        {
            if(tupleList[k].Item2 == currentColumn)
            {
                tmpList.Add(tupleList[k]);
            }
            else
            {
                StartCoroutine(UpdateHexagonPositions(height, width, tmpList));
                changedColumnAmount++;
                tmpList = new List<(int, int)>();
                tmpList.Add(tupleList[k]);
                currentColumn = tupleList[k].Item2;
            }
        }
        StartCoroutine(UpdateHexagonPositions(height, width, tmpList));
        changedColumnAmount++;



        recalculateWheels();

        //wait untill all the movement of all columns are finished to progress the game
        StartCoroutine(wait(changedColumnAmount, height, width));




    }

    IEnumerator wait(int changedColumnAmount, int height, int width)
    {
        while (finishedColumnCounter < changedColumnAmount)
        {
            yield return new WaitForSeconds(0.1f);
        }


        //keep popping the newly created hexagons that fall in popable positions untill no more popable hexagon trios left
        if (checkForPops())
        {
            bringDownColumns(height, width);
            audioSource.PlayOneShot(popClip, 1.0F);
        }
        else
        {
            rotating = false;
            //Check if there is any possible move left on the game
            if(!checkIfAnyMovesLeft(height, width))
            {
                audioSource.PlayOneShot(popClip, 1.0F);
                gameManager.GetComponent<GameManagerScript>().noMoreMovesLeft();
            }
        }
        
        
        

        
    }


    private GameObject bringDownSingleColumn(int height, int width, int rowNumber, int columnNumber)
    {

        for (int i = rowNumber; i < height-1; i++)
        {
            hexagonMatrix[i][columnNumber] = hexagonMatrix[i + 1][columnNumber];
        }
        Vector3 tmpPosition = positionMatrix[height - 1][columnNumber];
        //move newly created hexagons higher than game grid to create the falling effect
        tmpPosition.z += 2;

        gameManager.GetComponent<GameManagerScript>().addScore(5);


        //if the score just passed n*1000 create a bomb insted of a normal hexagon
        if ((gameManager.GetComponent<GameManagerScript>().getScore() / (1000 * bombCount)) > 0 )
        {
            hexagonMatrix[height - 1][columnNumber] = (GameObject)Instantiate(bombPrefab, tmpPosition, Quaternion.identity);
            bombCount++;
            gameManager.GetComponent<GameManagerScript>().addBomb(hexagonMatrix[height - 1][columnNumber]);
            hexagonMatrix[height - 1][columnNumber].transform.GetChild(1).GetComponent<Renderer>().material.SetColor("_Color", Color.black);
        }
        else
        {
            hexagonMatrix[height - 1][columnNumber] = (GameObject)Instantiate(hexagonPrefab, tmpPosition, Quaternion.identity);
        }

        
        Color randomColor = new Color();
        int randomColorIndex = Random.Range(0, usableColorArray.Length);
        ColorUtility.TryParseHtmlString(usableColorArray[randomColorIndex], out randomColor);


        hexagonMatrix[height - 1][columnNumber].name = "hexagon_" + usableColorArray[randomColorIndex];

        hexagonMatrix[height - 1][columnNumber].transform.GetChild(0).GetComponent<Renderer>().material.SetColor("_Color", randomColor);
        
        return (hexagonMatrix[height - 1][columnNumber]);

    }

    IEnumerator UpdateHexagonPositions(int height, int width, List<(int, int)> tupleList)
    {

        
        //Move hexagons to their places smoothly in the user interface
        for(int k=0; k< tupleList.Count; k++)
        {
            Vector3 targetPos = positionMatrix[tupleList[k].Item1][tupleList[k].Item2];
            float elapsedTime = 0;
            float waitTime = 0.1f;
            Vector3 currentPos = hexagonMatrix[tupleList[k].Item1][tupleList[k].Item2].transform.position;

            while (elapsedTime < waitTime)
            {
                hexagonMatrix[tupleList[k].Item1][tupleList[k].Item2].transform.position = Vector3.Lerp(currentPos, targetPos, (elapsedTime / waitTime));
                elapsedTime += Time.deltaTime;

                yield return null;
            }

            hexagonMatrix[tupleList[k].Item1][tupleList[k].Item2].transform.position = targetPos;
            yield return null;

        }

        finishedColumnCounter++;



    }


    private bool checkIfAnyMovesLeft(int height, int width)
    {

        /* To see if there is any valid moves left for player to perfom
         * we first find 2 neighbour hexagons with the same color
         * this 2 neighbours can be found in a directly vertical position
         * or in a diagonal position that points North West (horizontalNW) or North East (horizontalNE)
         * after we find the same color duos, we check the neighbours of the left and right slots that 
         * needs to be the same color as the duos. If there is any neighbours of these slots with the 
         * hexagon color we are looking for, we can say that there is at least 1 more possible move for the player
         * If however, we can't find any neighbours of any duos that can fit the slot with the necessary colored hexagon,
         * then we can safely say that there are no possible moves left for the player to gain more points.
         * Which means the game is over.
         * The traversing of the hexagons are being executed by direction specific functions like goNW and such
         * because movement in a hexagonal grid is more complex than moving in a flat 2d array. Moving one row up
         * and one row right can mean different result in coordinates differences depending on your position.
         */


        int i = 0;
        int j = 0;

        

        //vertical check
        while (i < height - 1)
        { 
            while(j < width)
            {
                if (hexagonMatrix[i][j].transform.name == hexagonMatrix[i + 1][j].transform.name)
                {
                    
                    if (verticalLeftNeighboursCheck(hexagonMatrix[i][j].transform.name, i, j, height, width))
                    {
                        return true;
                    }

                    if (verticalRightNeighboursCheck(hexagonMatrix[i][j].transform.name, i, j, height, width))
                    {
                        return true;
                    }

                }
                j++;
            }
            j = 0;
            i++;
        }

        i = 0;
        j = 0;
        int[] tmpCoordinates = new int[2];

        //horizontal check NW
        while (i < height)
        {
            while (j < width)
            {

                tmpCoordinates = goNW(i, j, height, width);

                if ((tmpCoordinates[0] < height) && (tmpCoordinates[0] > -1) && (tmpCoordinates[1] < width) && (tmpCoordinates[1] > -1))
                {
                    if (hexagonMatrix[i][j].transform.name == hexagonMatrix[tmpCoordinates[0]][tmpCoordinates[1]].transform.name)
                    {
                        if(i != 0)
                        {
                            if (horizontalNWLeftNeighboursCheck(hexagonMatrix[i][j].transform.name, i, j, height, width))
                            {
                                return true;
                            }
                        }
                        

                        if (horizontalNWRightNeighboursCheck(hexagonMatrix[i][j].transform.name, i, j, height, width))
                        {
                            return true;
                        }

                    }
                }
                
                j++;
            }
            j = 0;
            i++;
        }

        //horizontal check NE
        while (i < height)
        {
            while (j < width)
            {

                tmpCoordinates = goNE(i, j, height, width);
                if ((tmpCoordinates[0] < height) && (tmpCoordinates[0] > -1) && (tmpCoordinates[1] < width) && (tmpCoordinates[1] > -1))
                {
                    if (hexagonMatrix[i][j].transform.name == hexagonMatrix[tmpCoordinates[0]][tmpCoordinates[1]].transform.name)
                    {
                        if (i != 0)
                        {
                            if (horizontalNELeftNeighboursCheck(hexagonMatrix[i][j].transform.name, i, j, height, width))
                            {
                                return true;
                            }
                        }
                        

                        if (horizontalNERightNeighboursCheck(hexagonMatrix[i][j].transform.name, i, j, height, width))
                        {
                            return true;
                        }

                    }
                }

                j++;
            }
            j = 0;
            i++;
        }



        return false;

    }

    public bool horizontalNERightNeighboursCheck(string name, int i, int j, int height, int width)
    {


        int iTmp = i;
        int jTmp = j;
        int[] targetPositions = new int[2];

        //go south
        iTmp--;
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        targetPositions = goSE(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];

        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        

        targetPositions = goNE(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        //go north
        iTmp++;
        if ((iTmp < height) && (jTmp < width) && (iTmp > -1) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }



        return false;

    }





    public bool horizontalNELeftNeighboursCheck(string name, int i, int j, int height, int width)
    {


        int iTmp = i;
        int jTmp = j;
        int[] targetPositions = new int[2];

        targetPositions = goNW(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];

        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        //go north
        iTmp++;
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }


        targetPositions = goNE(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        


        targetPositions = goSE(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        return false;

    }

    public bool horizontalNWRightNeighboursCheck(string name, int i, int j, int height, int width)
    {


        int iTmp = i;
        int jTmp = j;
        int[] targetPositions = new int[2];

        targetPositions = goNE(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];

        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        //go north
        iTmp++;
        if ((iTmp < height) && (jTmp < width) && (iTmp > -1) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        targetPositions = goNW(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        


        targetPositions = goSW(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        return false;

    }




    public bool horizontalNWLeftNeighboursCheck(string name, int i, int j, int height, int width)
    {


        int iTmp = i;
        int jTmp = j;
        int[] targetPositions = new int[2];

        targetPositions = goSW(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];

        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }


        targetPositions = goNW(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        //go north
        iTmp++;
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }


        targetPositions = goNE(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        return false;

    }

    public bool verticalRightNeighboursCheck(string name, int i, int j, int height, int width)
    {


        int iTmp = i;
        int jTmp = j;
        int[] targetPositions = new int[2];

        targetPositions = goSE(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];

        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }


        targetPositions = goNE(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        //go north
        iTmp++;
        if ((iTmp < height) && (jTmp < width) && (iTmp > -1) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }


        targetPositions = goNW(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        return false;

    }




    public bool verticalLeftNeighboursCheck(string name, int i, int j, int height, int width)
    {


        int iTmp = i;
        int jTmp = j;
        int[] targetPositions = new int[2];

        targetPositions = goSW(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];

        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }
        

        targetPositions = goNW(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        //go north
        iTmp++;
        if ((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }
        

        targetPositions = goNE(iTmp, jTmp, height, width);
        iTmp = targetPositions[0];
        jTmp = targetPositions[1];
        if((iTmp < height) && (iTmp > -1) && (jTmp < width) && (jTmp > -1))
        {
            if (hexagonMatrix[iTmp][jTmp].transform.name == name)
            {
                return true;
            }
        }

        return false;

    }


    private int[] goNE(int i, int j, int height, int width)
    {
        int[] targetPositions = new int[2];

        if (j % 2 == 0)
        {
            targetPositions[0] = i;
        }
        else
        {
            targetPositions[0] = i+1;
        }

        targetPositions[1] = j + 1;


        return targetPositions;
        
    }

    private int[] goNW(int i, int j, int height, int width)
    {
        int[] targetPositions = new int[2];

        if (j % 2 == 0)
        {
            targetPositions[0] = i;
        }
        else
        {
            targetPositions[0] = i + 1;
        }

        targetPositions[1] = j - 1;


        return targetPositions;
    }

    private int[] goSE(int i, int j, int height, int width)
    {
        int[] targetPositions = new int[2];

        if (j % 2 == 0)
        {
            targetPositions[0] = i - 1;
        }
        else
        {
            targetPositions[0] = i;
        }

        targetPositions[1] = j + 1;


        return targetPositions;

    }

    private int[] goSW(int i, int j, int height, int width)
    {
        int[] targetPositions = new int[2];

        if (j % 2 == 0)
        {
            targetPositions[0] = i - 1;
        }
        else
        {
            targetPositions[0] = i;
        }

        targetPositions[1] = j - 1;

        return targetPositions;
    }




    void printHexagonMatrix(int height, int width, GameObject[][] hexagonMatrix)
    {
        for (int i = 0; i < height; i++)
        {
            for (int j = 0; j < width; j++)
            {
                string debugText = i.ToString() + ", " + j.ToString() + " - " + positionMatrix[i][j];

                Debug.Log(debugText);
            }
        }
    }
}
