<?php

namespace Medilies\TryingPhpGlfw;

use Exception;

class Engine
{
    private Window $window;

    public function __construct()
    {
        /**
         * Must initialize the GLFW library before using most GLFW functions.
         */
        if (!glfwInit()) {
            throw new Exception('GLFW could not be initialized!');
        }

        $this->window = new Window;

        glfwSwapInterval(1);
    }

    public function loop(): void
    {
        while (!$this->window->shouldClose()) {
            glfwPollEvents();

            // setting the clear color to black and clearing the color buffer
            [$mouseX, $mouseY] = $this->window->getCursorPos();

            glClearColor(sin($mouseX / 300), sin($mouseY / 300), cos($mouseY / 300), 1);
            glClear(GL_COLOR_BUFFER_BIT);

            $this->window->swapBuffers();
        }
    }

    public function __destruct()
    {
        /**
         * Before an application terminates GLFW should be terminated in order to free any resources allocated during or after initialization.
         * - If glfwInit fails, it calls glfwTerminate before returning.
         * - If it succeeds, you should call glfwTerminate before the application exits.
         */
        glfwTerminate();
    }
}
