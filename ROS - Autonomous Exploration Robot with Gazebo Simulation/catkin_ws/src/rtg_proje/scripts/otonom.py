#!/usr/bin/env python
import roslib; roslib.load_manifest('rtg_proje')
import rospy

from geometry_msgs.msg import Twist
from sensor_msgs.msg import LaserScan
import sys, select, termios, tty
from nav_msgs.msg import Odometry
import math
from math import cos, sin, degrees, radians, sqrt, pow
from tf.transformations import euler_from_quaternion
import tf

#desiredYaw = 0.0
#turning = 0
counter = 0
donusYonu = 0
def laser_callback(laserScan_data):
	global counter
	global donusYonu
	twist = Twist()
	i=380
	kapali = 0
	sol=0
	sag=0
	while(i<540):
		sag += laserScan_data.ranges[i]
		if(laserScan_data.ranges[i] < 0.6):
			kapali = 1
		i += 1
	while(i<700):
		sol += laserScan_data.ranges[i]
		if(laserScan_data.ranges[i] < 0.6):
			kapali = 1
		i += 1

	if(kapali == 0):
		

		twist.linear.x = 0.5
		twist.linear.y = 0
		twist.linear.z = 0

		twist.angular.x = 0
		twist.angular.y = 0
		twist.angular.z = 0

	else:
		if(counter == 0):
			if(sag > sol):
				donusYonu = -1
				counter = 100
				twist.linear.x = 0
				twist.linear.y = 0
				twist.linear.z = 0

				twist.angular.x = 0
				twist.angular.y = 0
				twist.angular.z = -1
			else:
				donusYonu = 1
				counter = 100
				twist.linear.x = 0
				twist.linear.y = 0
				twist.linear.z = 0

				twist.angular.x = 0
				twist.angular.y = 0
				twist.angular.z = 1
		else:
				twist.linear.x = 0
				twist.linear.y = 0
				twist.linear.z = 0

				twist.angular.x = 0
				twist.angular.y = 0
				twist.angular.z = donusYonu
				counter -= 1
		
		

	pub.publish(twist)
		

	'''
	global donus
	global desiredYaw
	global turning
	global counter

	print("Range[180]: " + str(laserScan_data.ranges[180]))
	print("Range[540]: " + str(laserScan_data.ranges[540]))
	print("Range[900]: " + str(laserScan_data.ranges[900]))
	speed = 0.3
	turnSpeed = 1.5
	twist = Twist()

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
	#print("Trans: " + "{:.2f}".format(tX) + " {:.2f}".format(tY) + " {:.2f}".format	(tZ))		
	#print("Roll: " + "{:.2f}".format(degrees(euler[0])) + " Pitch: " + "{:.2f}".format(degrees(euler[1]))
		# + " Yaw: " + "{:.2f}".format(degrees(euler[2])))
print("Yaw: " + "{:.2f}".format(degrees(euler[2])) + " Desired yaw: " + str(desiredYaw) + " Turning:" + str(turning))
 str(counter))
		counter -= 1

		if(counter ==0):
		if(kapali == 0):
			twist.linear.x = 0.5
			twist.linear.y = 0
			twist.linear.z = 0

			twist.angular.x = 0
			twist.angular.y = 0
			twist.angular.z = 0
		elif(laserScan_data.ranges[180] > laserScan_data.ranges[900]):
			twist.linear.x = 0
			twist.linear.y = 0
			twist.linear.z = 0

			twist.angular.x = 0
			twist.angular.y = 0
			twist.angular.z = -1
			counter = 90
		else:
			twist.linear.x = 0
			twist.linear.y = 0
			twist.linear.z = 0

			twist.angular.x = 0
			twist.angular.y = 0
			twist.angular.z = 1
			counter = 90	

		pub.publish(twist)
	else:
		print("Counter" +
	if(turning == 0):
		if(laserScan_data.ranges[540] > 1):
		
			twist.linear.x = speed
			twist.linear.y = 0
			twist.linear.z = 0

			twist.angular.x = 0
			twist.angular.y = 0
			twist.angular.z = 0

			pub.publish(twist)
		else:
			if(laserScan_data.ranges[180] > laserScan_data.ranges[900]):
				desiredYaw = degrees(euler[2]) + 90
				turning = 1
			else:
				desiredYaw = degrees(euler[2]) - 90
				turning = 1
			twist.linear.x = 0
			twist.linear.y = 0
			twist.linear.z = 0

			twist.angular.x = 0
			twist.angular.y = 0
			twist.angular.z = 0
					
			pub.publish(twist)
			
				
			

	else:
		print(abs(degrees(euler[2]) - desiredYaw))
		if(abs(degrees(euler[2]) - desiredYaw) < 2):	
			turning = 0
			twist.linear.x = 0
			twist.linear.y = 0
			twist.linear.z = 0

			twist.angular.x = 0
			twist.angular.y = 0
			twist.angular.z = 0
			pub.publish(twist)

		else:
			if(laserScan_data.ranges[180] > laserScan_data.ranges[900]):
		
				twist.linear.x = 0
				twist.linear.y = 0
				twist.linear.z = 0

				twist.angular.x = 0
				twist.angular.y = 0
				twist.angular.z = 1

				pub.publish(twist)

			else:

				twist.linear.x = 0
				twist.linear.y = 0
				twist.linear.z = 0

				twist.angular.x = 0
				twist.angular.y = 0
				twist.angular.z = -1	
				desiredYaw = 0
				turning = 0

				pub.publish(twist)
	'''


if __name__ == '__main__':
	rospy.init_node('otonomr', anonymous=True)
	pub = rospy.Publisher('cmd_vel', Twist, queue_size = 1)
	listener = tf.TransformListener()
	rospy.Subscriber("/robot/hokuyo", LaserScan, laser_callback)
	rospy.spin()
