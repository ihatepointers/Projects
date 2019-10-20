using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class TouchScreenScript : MonoBehaviour
{

    //Threshold for swiping distance
    private float minSwipeDistY = 50.0f;

    private Vector2 startPos;
    public GameObject gridCenter;
    public GameObject movementManager;
    public Camera cam;

    private void Start()
    {
        cam = GameObject.Find("Main Camera").GetComponent<Camera>();
        movementManager = GameObject.Find("MovementManager");

    }

    void Update()
    {
        //#if UNITY_ANDROID
        if (Input.touchCount > 0)
        {
            Touch touch = Input.touches[0];
            switch (touch.phase)
            {
                case TouchPhase.Began:
                    startPos = touch.position;
                    break;

                case TouchPhase.Ended:


                    //If there is a touch, check if its a swipe or click
                    float swipeDistVertical = (new Vector3(0, touch.position.y, 0) - new Vector3(0, startPos.y, 0)).magnitude;
                    if (swipeDistVertical > minSwipeDistY)
                    {

                        //find the swipe rotation
                        float swipeValue = Mathf.Sign(touch.position.y - startPos.y);

                        //clock rotation is different if the finger is on the left of the object or on the rigt
                        //set the rotation direction according to relative positions
                        if(touch.position.x > movementManager.GetComponent<MovementManagerScript>().getSelectedWheelPosX())
                        {
                            swipeValue = swipeValue * -1;
                        }

                        if (swipeValue > 0) {

                            movementManager.GetComponent<MovementManagerScript>().rotateWheel("clockwise");

                        }
                        else if (swipeValue < 0)
                        {
                            movementManager.GetComponent<MovementManagerScript>().rotateWheel("counterclockwise");
                        }
                    }
                    else if(swipeDistVertical == 0)
                    {
                        //if it is a click, check if it is a wheel
                        Ray raycast = Camera.main.ScreenPointToRay(Input.GetTouch(0).position);
                        RaycastHit raycastHit;
                        if (Physics.Raycast(raycast, out raycastHit))
                        {
                            Debug.Log("Something Hit");
                            if (raycastHit.collider.tag == "Wheel")
                            {
                                Vector3 screenPos = cam.WorldToScreenPoint(raycastHit.collider.transform.position);
                                Debug.Log("Touched the wheel");

                                movementManager.GetComponent<MovementManagerScript>().changeSelectedWheel(raycastHit.collider.gameObject, screenPos.x);

                            }
                        }
                    }
                    break;
            }


        }
    }
}
