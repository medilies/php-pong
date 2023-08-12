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

    private array $shaderPrograms = [];

    private array $elements = [];

    // GLFW does not inherently support multiple contexts within a single instance of the library.
    private function __construct()
    {
        echo '=================================================='.PHP_EOL;
        echo 'GLFW version: '.glfwGetVersionString().PHP_EOL;

        // Must initialize the GLFW library before using most GLFW functions.
        if (! glfwInit()) {
            throw new Exception('GLFW could not be initialized!');
        }
    }

    public function init(): void
    {
        // setting the swap interval to "1" basically enabled vsync.
        // more correctly it defines how many screen updates to wait for after swapBuffers has been called
        glfwSwapInterval(1);

        $this->createWindow();
    }

    public function loop(callable $callback): void
    {
        while (! $this->window->shouldClose()) {
            $callback($this);

            // Check and call events and swap the buffers
            $this->window->swapBuffers();
            glfwPollEvents();
        }
    }

    public function __destruct()
    {
        foreach ($this->elements as $element) {
            $element->delete();
        }

        // Free allocated any resources allocated
        glfwTerminate();
    }

    // ===============================================
    // ...
    // ===============================================

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

    public function getCurrentWindow(): Window
    {
        return $this->window;
    }

    public function registerShaderProgram(string $name, ShaderProgram $shaderProgram): void
    {
        $this->shaderPrograms[$name] = $shaderProgram;
    }

    public function useShaderProgram(string $name): void
    {
        $this->shaderPrograms[$name]->use();
    }

    public function useShaderProgramIfExists(string $name): void
    {
        if (! isset($this->shaderPrograms[$name])) {
            return;
        }

        $this->shaderPrograms[$name]->use();
    }

    public function getShaderProgramRef(string $name): int
    {
        return $this->shaderPrograms[$name]->getRef();
    }

    public function registerElement(string $name, Element $element): void
    {
        $this->elements[$name] = $element;
    }

    public function bindVertexArray(string $name): void
    {
        $this->elements[$name]->bind();
    }
}
