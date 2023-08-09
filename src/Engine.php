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

        $this->createWindow()
            ->bindContext();
    }

    private function createWindow(): static
    {
        $this->window = new Window;

        return $this;
    }

    private function bindContext(): static
    {
        glfwMakeContextCurrent($this->window->ref);
        glfwSwapInterval(1);

        return $this;
    }

    public function loop(): void
    {
        while (!glfwWindowShouldClose($this->window->ref)) {
            glfwPollEvents();

            // setting the clear color to black and clearing the color buffer
            glfwGetCursorPos($this->window->ref, $mouseX, $mouseY);
            glClearColor(sin($mouseX / 300), sin($mouseY / 300), cos($mouseY / 300), 1);
            glClear(GL_COLOR_BUFFER_BIT);

            // swap the windows framebuffer and
            // poll queued window events.
            glfwSwapBuffers($this->window->ref);
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
