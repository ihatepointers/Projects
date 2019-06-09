#!/usr/bin/env python
# rospy for the subscriber
import rospy
# ROS Image message
from sensor_msgs.msg import Image
# ROS Image message -> OpenCV2 image converter
from cv_bridge import CvBridge, CvBridgeError
# OpenCV2 for saving an image
import cv2 as cv

# Instantiate CvBridge
bridge = CvBridge()

pub = rospy.Publisher('detected_objects', Image)

classesFile = "/home/serdar/catkin_ws/src/rtg_proje/scripts/yolov3.names"
classes = None
with open(classesFile, 'rt') as f:
    classes = f.read().rstrip('\n').split('\n')

def image_callback(msg):
	try:
		cv2_img = bridge.imgmsg_to_cv2(msg, "bgr8")
	except CvBridgeError, e:
		print(e)
	else:
		with open("/home/serdar/output.txt", 'rt') as f:
			preds = f.read().rstrip('\n').split('\n')
		#print(preds[0])
		if(preds[0] != ""):
			cv2_img = drawPred(preds,msg)
			pub.publish(bridge.cv2_to_imgmsg(cv2_img, "bgr8"))
			#cv.imshow("asd",cv2_img)
			#cv.waitKey(0)
		else:
			pub.publish(bridge.cv2_to_imgmsg(cv2_img, "bgr8"))


def drawPred(preds,msg):
#print(len(preds))
	try:
		#cap = cv.VideoCapture("/home/serdar/camera_image.jpg")
		#hasFrame, frame = cap.read()
		frame = bridge.imgmsg_to_cv2(msg, "bgr8")
	except CvBridgeError, e:
		print(e)
	else:
		for pred in preds:
			classId, conf, left, top, right, bottom = pred.split(" ")
			classId = int(classId)
			conf = float(conf)
			left = int(left)
			top = int(top)
			right = int(right)
			bottom = int(bottom)
			#print(classId)
			#print(conf)
			#print(left)
			#print(top)
			#print(right)
			#print(bottom)
			# Draw a bounding box.
			#cv.rectangle(frame, int(left, top), int(right, bottom), (255, 178, 50), 3)
			cv.rectangle(frame, (left, top), (right, bottom), (255, 178, 50), 3)

			label = '%.2f' % conf

			if classes:
				assert(classId < len(classes))
				label = '%s:%s' % (classes[classId], label)

			labelSize, baseLine = cv.getTextSize(label, cv.FONT_HERSHEY_SIMPLEX, 0.5, 1)
			top = max(top, labelSize[1])
			#cv.rectangle(frame, (left, top - round(1.5*labelSize[1])), (left + round(1.5*labelSize[0]), top + baseLine), (255, 255, 255), cv.FILLED)
			cv.putText(frame, label, (left, top), cv.FONT_HERSHEY_SIMPLEX, 0.75, (0,0,0), 1)
		return frame

def listener():

    rospy.init_node('object_detector', anonymous=True)

    rospy.Subscriber("/robot/camera/rgb/image_raw", Image, image_callback)

    # spin() simply keeps python from exiting until this node is stopped
    rospy.spin()

if __name__ == '__main__':
    listener()
