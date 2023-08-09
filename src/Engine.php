<?php

namespace Medilies\TryingPhpGlfw;

use GLFWwindow;

class Engine
{
    private GLFWwindow $window;

    public function __construct()
    {
        /**
         * Must initialize the GLFW library before using most GLFW functions.
         */
        if (!glfwInit()) {
            throw new Exception('GLFW could not be initialized!');
        }

        $this->setWindowProps()
            ->createWindow()
            ->bindContext();
    }

    private function setWindowProps(): static
    {
        // allow the window to be resized by the user
        glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);

        // set the OpenGL context version and profile 
        glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, 4);
        glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, 1);
        glfwWindowHint(GLFW_OPENGL_PROFILE, GLFW_OPENGL_CORE_PROFILE);

        // enable forward compatibility, @see glfw docs for details
        glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);

        return $this;
    }

    private function createWindow(): static
    {
        if (!$this->window = glfwCreateWindow(800, 600, "PHP GLFW Demo")) {
            throw new Exception('OS Window could not be initialized!');
        }

        return $this;
    }

    private function bindContext(): static
    {
        glfwMakeContextCurrent($this->window);
        glfwSwapInterval(1);

        return $this;
    }

    public function loop(): void
    {
        while (!glfwWindowShouldClose($this->window)) {
            glfwPollEvents();

            // setting the clear color to black and clearing the color buffer
            glfwGetCursorPos($this->window, $mouseX, $mouseY);
            glClearColor(sin($mouseX / 300), sin($mouseY / 300), cos($mouseY / 300), 1);
            glClear(GL_COLOR_BUFFER_BIT);

            // swap the windows framebuffer and
            // poll queued window events.
            glfwSwapBuffers($this->window);
        }
    }

    public function __destruct()
    {
        /**
         * Before an application terminates GLFW should be terminated in order to free any resources allocated during or after initialization.
         * - If glfwInit fails, it calls glfwTerminate before returning.
         * - If it succeeds, you should call glfwTerminate before the application exits.
         */
        glfwDestroyWindow($this->window);
        glfwTerminate();
    }
}
