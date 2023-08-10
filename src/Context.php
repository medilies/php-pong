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
        // OpenGL spec
        glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, $this->max_version);
        glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, $this->min_version);
        glfwWindowHint(GLFW_OPENGL_PROFILE, $this->profile);

        // Resizable
        glfwWindowHint(GLFW_RESIZABLE, GL_FALSE);

        // Required to run on Mac OS X
        glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);

        $this->window = new Window(800, 600, 'Hello PHP GLFW');
        // glViewport(0,0,800,600); // ! This breaks the process

        // This function makes the OpenGL or OpenGL ES context of the specified window current on the calling thread. A context must only be made current on a single thread at a time and each thread can have only a single current context at a time.
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
