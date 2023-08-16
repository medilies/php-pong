<?php

namespace Medilies\TryingPhpGlfw;

use Exception;
use GL\Math\Mat4;
use Medilies\TryingPhpGlfw\Common\BasicSingletonTrait;
use Medilies\TryingPhpGlfw\Nodes\Node;

class Context
{
    use BasicSingletonTrait;

    private int $min_version = 3;

    private int $max_version = 4;

    private int $profile = GLFW_OPENGL_CORE_PROFILE;

    private Window $window;

    /** @var array<string, ShaderProgram> */
    private array $shaderPrograms = [];

    /** @var array<string, int> */
    private array $uniformLocations = [];

    /** @var array<string, Node> */
    private array $nodes = [];

    // GLFW does not inherently support multiple contexts within a single instance of the library.
    private function __construct()
    {
        echo '=================================================='.PHP_EOL;
        echo 'GLFW version: '.glfwGetVersionString().PHP_EOL;

        // Must initialize the GLFW library before using most GLFW functions.
        if (! glfwInit()) {
            throw new Exception('GLFW could not be initialized!');
        }

        // setting the swap interval to "1" basically enabled vsync.
        // more correctly it defines how many screen updates to wait for after swapBuffers has been called
        glfwSwapInterval(1);
    }

    public function loop(callable $callback): void
    {
        while (! $this->window->shouldClose()) {
            $callback($this);

            foreach ($this->nodes as $key => $node) {
                $node->move();
            }

            foreach ($this->nodes as $key => $node) {
                $node->draw();
            }

            // TODO: detect collisions

            foreach ($this->nodes as $key => $node) {
                $node->postDraw();
            }

            $this->window->swapBuffers();
            glfwPollEvents();
        }
    }

    // public function reset(): void
    // {
    //     foreach ($this->nodes as $key => $node) {
    //         $node->reset();
    //     }
    // }

    /**
     * Free allocated resources
     */
    public function __destruct()
    {
        foreach ($this->shaderPrograms as $shaderProgram) {
            $shaderProgram->delete();
        }

        glfwTerminate();
    }

    // ===============================================
    // Window
    // ===============================================

    public function createWindow($width, $height, $title): static
    {
        // ? allow many calls
        // TODO: move logic to window build
        // OpenGL spec
        glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, $this->max_version);
        glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, $this->min_version);
        glfwWindowHint(GLFW_OPENGL_PROFILE, $this->profile);

        // Resizable
        glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);

        // Required to run on Mac OS X
        glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);

        $this->window = new Window($width, $height, $title);

        $this->window->makeCurrentContext();

        return $this;
    }

    public function getCurrentWindow(): Window
    {
        return $this->window;
    }

    public function updateViewport(): void
    {
        $this->getCurrentWindow()->setViewport();
    }

    public function isPressed(int $glfwKeyCode): bool
    {
        return $this->getCurrentWindow()->isPressed($glfwKeyCode);
    }

    public function closeCurrentWindow(): void
    {
        $this->getCurrentWindow()->close();
    }

    public function getCurrentWindowWidth(): int
    {
        return $this->getCurrentWindow()->getWidth();
    }

    public function getCurrentWindowHeight(): int
    {
        return $this->getCurrentWindow()->getHeight();
    }

    // ===============================================
    // Shaders
    // ===============================================

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

    // -----------------------------------------------
    // Uniforms
    // -----------------------------------------------
    public function registerUniformLocation(string $shaderName, string $name): int
    {
        // TODO: do not allow override and '' name
        // ? add method to override
        $location = glGetUniformLocation(
            $this->getShaderProgramRef($shaderName),
            $name
        );

        $this->uniformLocations[$name] = $location;

        return $location;
    }

    public function getUniformLocation(string $name): int
    {
        return $this->uniformLocations[$name];
    }

    public function setUniform4f(string $name, bool $transpose, Mat4 $matrix): void
    {
        // note that we use `glUniformMatrix4f` instead of `glUniformMatrix4fv` to pass a single matrix.
        glUniformMatrix4f($this->getUniformLocation($name), $transpose, $matrix);
    }

    public function setUniform1i(string $name, int $value): void
    {
        glUniform1i($this->getUniformLocation($name), $value);
    }

    // ===============================================
    // Nodes
    // ===============================================

    public function registerNode(string $name, Node $node): void
    {
        // TODO: must not be '' or duplicate
        $this->nodes[$name] = $node;
    }

    public function unregisterNode(string $name): void
    {
        unset($this->nodes[$name]);
    }

    public function getNode(string $name): Node
    {
        return $this->nodes[$name];
    }
}
