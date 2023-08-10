<?php

// !⚠️ DO NOT INCLUDE THIS, IT IS JUST SUPPOSED TO BE DETECTED BY THE IDE AND ANALYZE SIGNATURES!

class GLFWwindow {}
class GLFWmonitor{}

/**
 * @return int GLFW_TRUE if successful, or GLFW_FALSE if an error occurred.
 */
function glfwInit() : int
{
}

/**
 * @param int $interval The minimum number of screen updates to wait for until the buffers are swapped by glfwSwapBuffers.
 */
function glfwSwapInterval(int $interval) : void
{
}

/**
 * 
 */
function glfwPollEvents() : void
{
}

/**
 * TODO ...
 */
function glClearColor(float $red, float $green, float $blue, float $alpha): mixed
{
}

/**
 * TODO ...
 */
function glClear(int $mask): void
{
}

/**
 * 
 */
function glfwTerminate() : void
{
}

/**
 * @param GLFWwindow $window The window whose context to make current, or NULL to detach the current context.
 */
function glfwMakeContextCurrent(GLFWwindow $window): void
{
}

/**
 * @param GLFWwindow $window The window to query.
 * @return int The value of the close flag.
 */
function glfwWindowShouldClose(GLFWwindow $window) : int
{
}

/**
 * @param GLFWwindow $window The window whose buffers to swap.
 */
function glfwSwapBuffers(GLFWwindow $window) : void
{
}

/**
 * @param GLFWwindow $window The desired window.
 * @param float $xpos Where to store the cursor x-coordinate, relative to the left edge of the content area, or NULL.
 * @param float $ypos Where to store the cursor y-coordinate, relative to the to top edge of the content area, or NULL.
 */
function glfwGetCursorPos(GLFWwindow $window, float &$xpos, float &$ypos) : void
{
}

/**
 * @param int $hint The window hint to set.
 * @param int $value The new value of the window hint.
 */
function glfwWindowHint(int $hint, int $value) : void
{
}

/**
 * @param int $width The desired width, in screen coordinates, of the window. This must be greater than zero.
 * @param int $height The desired height, in screen coordinates, of the window. This must be greater than zero.
 * @param string $title The initial, UTF-8 encoded window title.
 * @param ?GLFWmonitor $monitor The monitor to use for full screen mode, or NULL for windowed mode.
 * @param ?GLFWwindow $share The window whose context to share resources with, or NULL to not share resources.
 */
function glfwCreateWindow(int $width, int $height, string $title, ?GLFWmonitor $monitor = null, ?GLFWwindow $share = null) : GLFWwindow
{
}

/**
 * @param GLFWwindow $window The window to destroy.
 */
function glfwDestroyWindow(GLFWwindow $window) : void
{
}
