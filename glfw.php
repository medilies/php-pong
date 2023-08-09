<?php

if (!glfwInit()) {
    throw new Exception('GLFW could not be initialized!');
}

// !

// allow the window to be resized by the user
glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);

// set the OpenGL context version and profile 
glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, 4);
glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, 1);
glfwWindowHint(GLFW_OPENGL_PROFILE, GLFW_OPENGL_CORE_PROFILE);

// enable forward compatibility, @see glfw docs for details
glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);

// !

if (!$window = glfwCreateWindow(800, 600, "PHP GLFW Demo")) {
    throw new Exception('OS Window could not be initialized!');
}

// !

glfwMakeContextCurrent($window);

// !

glfwSwapInterval(1); #

// !

while (!glfwWindowShouldClose($window)) {
    glfwPollEvents();

    // setting the clear color to black and clearing the color buffer
    glfwGetCursorPos($window, $mouseX, $mouseY);
    glClearColor(sin($mouseX / 300), sin($mouseY / 300), cos($mouseY / 300), 1);
    glClear(GL_COLOR_BUFFER_BIT);

    // swap the windows framebuffer and
    // poll queued window events.
    glfwSwapBuffers($window);
}

// !

glfwDestroyWindow($window);
glfwTerminate();
