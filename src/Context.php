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

    // GLFW does not inherently support multiple contexts within a single instance of the library.
    private function __construct()
    {
        echo glfwGetVersionString().PHP_EOL;

        // Must initialize the GLFW library before using most GLFW functions.
        if (! glfwInit()) {
            throw new Exception('GLFW could not be initialized!');
        }

        // setting the swap interval to "1" basically enabled vsync.
        // more correctly it defines how many screen updates to wait for after swapBuffers has been called
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
        glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);

        // Required to run on Mac OS X
        glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);

        $this->window = new Window(800, 600, 'Hello PHP GLFW');

        $this->window->makeCurrentContext();

        return $this;
    }

    public function loop(): void
    {
        while (! $this->window->shouldClose()) {
            // Close input event
            if (glfwGetKey($this->window->getRef(), GLFW_KEY_ESCAPE) == GLFW_PRESS) {
                glfwSetWindowShouldClose($this->window->getRef(), GL_TRUE);
            }

            // setting the clear color to black and clearing the color buffer
            [$mouseX, $mouseY] = $this->window->getCursorPos();

            // Render
            glClearColor(sin($mouseX / 300), sin($mouseY / 300), cos($mouseY / 300), 1);
            glClear(GL_COLOR_BUFFER_BIT);

            // Check and call events and swap the buffers
            $this->window->swapBuffers();
            glfwPollEvents();
        }
    }

    public function __destruct()
    {
        // Free allocated any resources allocated
        glfwTerminate();
    }
}
