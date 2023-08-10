<?php

namespace Medilies\TryingPhpGlfw;

use Exception;
use Medilies\TryingPhpGlfw\Common\BasicSingletonTrait;

class Context
{
    use BasicSingletonTrait;

    private int $min_version = 3;

    private int $max_version = 4;

    private int $profile = GLFW_OPENGL_CORE_PROFILE;

    private Window $window;

    private function __construct()
    {
        // Must initialize the GLFW library before using most GLFW functions.
        if (!glfwInit()) {
            throw new Exception('GLFW could not be initialized!');
        }

        glfwSwapInterval(1);

        $this->createWindow();
    }

    private function createWindow(): static
    {
        glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, $this->max_version);
        glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, $this->min_version);
        glfwWindowHint(GLFW_OPENGL_PROFILE, $this->profile);

        glfwWindowHint(GLFW_RESIZABLE, GL_FALSE);

        glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);

        $this->window = new Window(800, 600, 'Hello PHP GLFW');

        $this->window->makeCurrentContext();

        return $this;
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
        // Free allocated any resources allocated
        glfwTerminate();
    }
}
