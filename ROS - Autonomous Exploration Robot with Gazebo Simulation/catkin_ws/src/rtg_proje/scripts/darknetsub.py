#!/usr/bin/env python
# rospy for the subscriber
import rospy
import time
import roslib
import tf
from std_msgs.msg import String
from sensor_msgs.msg import Image
from sensor_msgs.msg import LaserScan
from geometry_msgs.msg import Twist
from geometry_msgs.msg import Point
from darknet_ros_msgs.msg import BoundingBoxes
from darknet_ros_msgs.msg import BoundingBox
from cv_bridge import CvBridge, CvBridgeError
from array import *
import cv2
import message_filters
from visualization_msgs.msg import Marker, MarkerArray
from nav_msgs.msg import Odometry
import math
from math import cos, sin, degrees, radians, sqrt, pow
from tf.transformations import euler_from_quaternion
import qrtools
from pyzbar import pyzbar
#from geometry_msgs.msg import pose



markerArrayID = 0

def img_callback(msg):
	cv2_img = bridge.imgmsg_to_cv2(msg, "bgr8")
	return cv2_img

def callback(bbox_data, laserScan_data):
	
	nameArray = []
	xMinArray = []
	yMinArray = []
	xMaxArray = []
	yMaxArray = []
	
	detected_Objects = [[]]
	
	global id_stock
	global markerArray
	global subArray
	

	
	i=0
	
	#Bounding box verisini kontrol el	
	if(bbox_data is not None):
		for i in range (len(bbox_data.bounding_boxes)):
			try:
				tmp = bbox_data.bounding_boxes[i].Class
				nameArray.append(bbox_data.bounding_boxes[i].Class)
				xMinArray.append(bbox_data.bounding_boxes[i].xmin)
				yMinArray.append(bbox_data.bounding_boxes[i].ymin)
				xMaxArray.append(bbox_data.bounding_boxes[i].xmax)
				yMaxArray.append(bbox_data.bounding_boxes[i].ymax)
				i += 1
			except:
				print("hata")
	
	objCount = i
	counter = 0
	#Laser scan verisinden obje uzakligini bul
	for counter in range(objCount):
			rangeMin= 380 + ((640 - xMaxArray[counter])/2)
			rangeMax= 700 - ( xMinArray[counter]/2)
		
			sum = 0
			k=0
			'''
			for j in range (int(round(rangeMax-rangeMin))):
				sum += laserScan_data.ranges[rangeMin+j]
			mean = sum/(rangeMax-rangeMin)
			'''
			midPoint = int(round((rangeMin+rangeMax)/2))
			mean = laserScan_data.ranges[midPoint]
			detected_Objects.append([nameArray[counter], mean, midPoint])


	i = 1
	
	print("Bulunan obje sayisi: " + str(len(detected_Objects)))
	print(detected_Objects)
	print("   | Class " + " | Dist  | " + "pos |")
	while(i<len(detected_Objects)): 		#Bulunan objeleri yazdir
		text = " "		
		text += str(i)+ ".| "
		text += str(detected_Objects[i][0])
		text +=  " | "
		text += "{:.2f}".format(detected_Objects[i][1])
		text +=  "m | "
		text += str(detected_Objects[i][2])
		text +=  " | "
		print(text)

		#Marker olustur
		marker = Marker()
		marker.header.frame_id = "/robot/odom"
		marker.header.stamp = rospy.get_rostime()
		marker.id = id_stock[0]
		id_stock.pop(0)
		marker.ns = str(detected_Objects[i][0])
		marker.type = marker.SPHERE
		marker.action = marker.ADD
		marker.scale.x = 0.2
		marker.scale.y = 0.2
		marker.scale.z = 0.2
		marker.color.a = 0.1
		marker.color.r = 1.0
		marker.color.g = 0.0
		marker.color.b = 0.0
		marker.pose.orientation.w = 1.0

		#Robotun global koordinatlarini bul
		(trans,rot) = listener.lookupTransform('/robot/odom', '/robot/base_link',rospy.Time(0))
		tX = trans[0]
		tY = trans[1]
		tZ = trans[2]
	
		rX = rot[0]
		rY = rot[1]
		rZ = rot[2]
		rW = rot[3]
		quaternion = (
		    rX,
		    rY,
		    rZ,
		    rW)
		euler = tf.transformations.euler_from_quaternion(quaternion)	
		print("Trans: " + "{:.2f}".format(tX) + " {:.2f}".format(tY) + " {:.2f}".format	(tZ))		
		print("Roll: " + "{:.2f}".format(degrees(euler[0])) + " Pitch: " + "{:.2f}".format(degrees(euler[1]))
			 + " Yaw: " + "{:.2f}".format(degrees(euler[2])))

		#Kameranin merkezine gore obje acisini bul
		if(detected_Objects[i][2] > 539):
			degree = (float(detected_Objects[i][2])-539) * laserScan_data.angle_increment	
		else:
			degree = (539-float(detected_Objects[i][2])) * laserScan_data.angle_increment
	
		degree = degrees(degree)
	
		#Global aciyi bul
		if(detected_Objects[i][2] < 540):
			degree = degree * (-1)
		yaw = round(degrees(euler[2]),2)

		globalYaw = yaw + degree
	
		print("ObjeDegree: " + str(degree) + " Yaw: " + str(yaw) +" Global Yaw: " + str(globalYaw))	

		xAdd = detected_Objects[i][1]*cos(radians(globalYaw)) 
		yAdd = detected_Objects[i][1]*sin(radians(globalYaw))

		marker.pose.position.x = trans[0]
		marker.pose.position.y = trans[1]
		marker.pose.position.z = 0.2

		marker.pose.position.x += xAdd
		marker.pose.position.y += yAdd
		j=0
		different = True
		sameID = -1
		pointIncRate = 0.1
		pointDecRate = 0.03
	
		#Bulunan obje daha once isaretlenmis mi diye kontrol et
		while (j <(len(subArray.markers))):
			if(subArray.markers[j].ns == str(detected_Objects[i][0])):
				x1 = subArray.markers[j].pose.position.x
				x2 = marker.pose.position.x
				y1 = subArray.markers[j].pose.position.y
				y2 = marker.pose.position.y
				distanceBetween = sqrt(pow((x1-x2),2)+pow((y1-y2),2))
				print("Distance between:" + str(distanceBetween))
				if(distanceBetween != float("inf")):
					if(subArray.markers[j].color.a < 1.0):
						if( distanceBetween < 0.1):
							print("Color.a " + str(subArray.markers[j].color.a))
							different = False
							sameID = j
					else:
						if( distanceBetween < 1):
							print("Color.a2 " + str(subArray.markers[j].color.a))
							different = False
							sameID = j

			j += 1

			


		if(different):
			#marker.id = id_stock[len(id_stock)-1]
			#id_stock.pop(len(id_stock)-1)
			subArray.markers.append(marker)
		else:
			id_stock.append(marker.id)
				
			
			if(subArray.markers[sameID].color.a < 1.0):
				subArray.markers[sameID].color.a += pointIncRate
				if(subArray.markers[sameID].color.a >= 1.0):
			
					
					
					#subArray.markers[sameID].text= str(detected_Objects[i][0])
					
					tmpMarker = Marker()
					tmpMarker.header.frame_id = "/robot/odom"
					tmpMarker.header.stamp = rospy.get_rostime()
					tmpMarker.id = subArray.markers[sameID].id
					tmpMarker.ns = "textmarker"
					tmpMarker.type = marker.TEXT_VIEW_FACING
					tmpMarker.action = marker.ADD
					tmpMarker.scale.x = 0.2
					tmpMarker.scale.y = 0.2
					tmpMarker.scale.z = 0.2
					tmpMarker.color.a = 1.0
					tmpMarker.color.r = 0.0
					tmpMarker.color.g = 0.0
					tmpMarker.color.b = 1.0

					#print("MarkerPoseZ: " + str(subArray.markers[sameID].pose.position.z) + " TextPoseZ: " + str(tmpMarker.pose.position.z))
					tmpMarker.pose.orientation.w = 1.0
					tmpMarker.pose.position.x = subArray.markers[sameID].pose.position.x
					tmpMarker.pose.position.y = subArray.markers[sameID].pose.position.y
					tmpMarker.pose.position.z = subArray.markers[sameID].pose.position.z
					tmpMarker.pose.position.z += 0.2
					if(str(subArray.markers[sameID].ns) == "QR"):
						tmpMarker.text = "QR_"
						print("i: " + str(i))
						tmp_xMin = xMinArray[i-1]
						tmp_yMin = yMinArray[i-1]
						tmp_xMax = xMaxArray[i-1]
						tmp_yMax = yMaxArray[i-1]
						img = rospy.wait_for_message("/robot/camera/rgb/image_raw",Image)
						cv2_img = bridge.imgmsg_to_cv2(img, "bgr8")
						#cv2.imshow("asf",cv2_img)
						#time.sleep(1)
						#time.sleep(10)
						#print("Xmin: " +str(tmp_xMin)+ " yMin: " +str(tmp_yMin)+ " tmp_xMax: " +str(tmp_xMax)+ " tmp_yMax: "+str(tmp_yMax))
						if(tmp_xMin > 20):
							tmp_xMin -= 20
						if(tmp_yMin > 20):
							tmp_yMin -= 20
						if(tmp_xMax < 620):
							tmp_xMax += 20
						if(tmp_yMax < 460):
							tmp_yMax += 20
						#print("Xmin: " +str(tmp_xMin)+ " yMin: " +str(tmp_yMin)+ " tmp_xMax: " +str(tmp_xMax)+ " tmp_yMax: "+str(tmp_yMax))
						#print("tmp_yMin: "+ str(tmp_yMin) + " tmp_yMax-tmp_yMin: "+ str(tmp_yMax-tmp_yMin) + "tmp_xMin: "+ str(tmp_xMin) + " tmp_xMax-tmp_xMin" + str(tmp_xMax-tmp_xMin))
						cv2_img = cv2_img[tmp_yMin:tmp_yMax,tmp_xMin:tmp_xMax]
						#qr = qrtools.QR()
						#qr.decode(cv2_img)
						qrData = pyzbar.decode(cv2_img)
						#print("QR data:" + str(qrData[0][0]))
						#time.sleep(10)
						#print(cv2_img)
						#cv2.imshow("QRimg",cv2_img)
						#cv2.waitKey(1)

						#img = rospy.Subscriber("/robot/camera/rgb/image_raw", Image, img_callback)
						
						#print("QR hata")
						#cv2.imshow(img)
						#cv2.waitKey(1)
						#time.sleep(10)
						if(qrData):
							subArray.markers[sameID].color.a = 1.0
							subArray.markers[sameID].color.r = 0.0
							subArray.markers[sameID].color.g = 1.0
							tmpMarker.text += str(qrData[0][0])
							subArray.markers.append(tmpMarker)
							#markerArray.markers.append(tmpMarker)
							print("qr okundu")
						else:
							
							subArray.markers[sameID].color.a = 0.2
							print("qr okunamadi")
							#time.sleep(10)
						#time.sleep(10)
						
						
					else:
						subArray.markers[sameID].color.a = 1.0
						subArray.markers[sameID].color.r = 0.0
						subArray.markers[sameID].color.g = 1.0
						tmpMarker.text = subArray.markers[sameID].ns
						subArray.markers.append(tmpMarker)
						#markerArray.markers.append(tmpMarker)
					#print("MarkerPoseZ: " + str(subArray.markers[sameID].pose.position.z) + " TextPoseZ: " + str(tmpMarker.pose.position.z))
					#time.sleep(10)
					
					
					
					
					
					
					#markerArray.markers.append(subArray.markers[sameID])
					
					
					print(subArray.markers[sameID])

					j=0
					while (j <(len(subArray.markers))):
						if(subArray.markers[j].ns == str(detected_Objects[i][0])):
							x1 = subArray.markers[j].pose.position.x
							x2 = marker.pose.position.x
							y1 = subArray.markers[j].pose.position.y
							y2 = marker.pose.position.y
							distanceBetween = sqrt(pow((x1-x2),2)+pow((y1-y2),2))
							if(distanceBetween != float("inf")):
								if((subArray.markers[j].color.a < 1.0) and (distanceBetween < 1)):
									subArray.markers[j].color.a = 0.0
						j += 1
					
	
		i += 1
		
		print("\n")
	j=0
	deleteArray = []
	marker_pub.publish(subArray)
	while j < (len(subArray.markers)):
		if((subArray.markers[j].ns == "QR") and (subArray.markers[j].color.a >= 1.0)):
			print("lensub" + str(len(subArray.markers)) +" id: " + str(subArray.markers[j].id) + 
				" NS: " + str(subArray.markers[j].ns) +" text:"+ str(subArray.markers[j].text) +" color.a: " + str(subArray.markers[j].color.a)) 

		if(subArray.markers[j].color.a < 1.0):

			print("dec")
			print("lensub" + str(len(subArray.markers)) +" id: " + str(subArray.markers[j].id) + 
						" NS: " + str(subArray.markers[j].ns) +" text:"+ str(subArray.markers[j].text) +" color.a: " + str(subArray.markers[j].color.a)) 
			if(subArray.markers[j] > 0.0):
				subArray.markers[j].color.a -= pointDecRate
			if(subArray.markers[j].color.a <= 0.0):
				deleteArray.append(subArray.markers[j])
		j += 1
	if(deleteArray is not None):
		deleteArray.sort(reverse = True)
		for j in range(len(deleteArray)):
			id_stock.append(deleteArray[j].id)
			subArray.markers.remove(deleteArray[j])
	#marker_pub.publish(subArray)
	#marker_pub.publish(markerArray)
	print("Marker sayisi: " + str(len(markerArray.markers)))
	print("id_stock: " + str(len(id_stock)))
	

if __name__ == '__main__':
	bridge = CvBridge()
	rospy.init_node('darknetsubs', anonymous=True)
	markerArray = MarkerArray()
	subArray = MarkerArray()
	id_stock = []
	for i in range(1000):
		id_stock.append(i)
	id_stock.sort()	
	listener = tf.TransformListener()
	marker_pub = rospy.Publisher("markers", MarkerArray, queue_size = 100)
	bbox_sub = message_filters.Subscriber("/darknet_ros/bounding_boxes", BoundingBoxes)
	laserScan_sub = message_filters.Subscriber("/robot/hokuyo", LaserScan)
	
	#marker_sub = message_filters.Subscriber("/markers", MarkerArray)
	ts = message_filters.TimeSynchronizer([bbox_sub, laserScan_sub], 10)
	ts.registerCallback(callback)

	rospy.spin()
