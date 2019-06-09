cmake_minimum_required(VERSION 2.8.3)
project(rtg_proje)
set(CMAKE_AUTOMOC ON)

find_package(catkin REQUIRED
  std_msgs
  message_generation
)

find_package(catkin REQUIRED COMPONENTS
  geometry_msgs
  gazebo_ros
  actionlib_msgs
  geometry_msgs
  sensor_msgs
  std_msgs
  message_generation
  darknet_ros
)


catkin_package(
  CATKIN_DEPENDS
    actionlib_msgs
    geometry_msgs
    sensor_msgs
    message_runtime
    std_msgs
)

include_directories(${catkin_INCLUDE_DIRS})
add_dependencies(darknet_ros ${${PROJECT_NAME}_EXPORTED_TARGETS} ${catkin_EXPORTED_TARGETS})




catkin_install_python(PROGRAMS scripts/keyboard_cmdvel.py DESTINATION ${CATKIN_PACKAGE_BIN_DESTINATION})
