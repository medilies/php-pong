<?php

namespace Medilies\TryingPhpGlfw;

use Exception;
use GLFWwindow;

class Window
{
    private GLFWwindow $windowRef;

    public function __construct(
        private int $width,
        private int $height,
        private string $title,
    ) {
        $this->setProps();
        $this->create();
    }
    
    // ===============================================
    // ...
    // ===============================================
    
    public function makeCurrentContext(): void
    {
        glfwMakeContextCurrent($this->windowRef); 
    }

    public function shouldClose(): int
    {
        return glfwWindowShouldClose($this->windowRef);
    }

    public function swapBuffers(): void
    {
        // swap the windows framebuffer and
        // poll queued window events.
        glfwSwapBuffers($this->windowRef);
    }

    public function getCursorPos(): array
    {
        glfwGetCursorPos($this->windowRef, $mouseX, $mouseY);

        return [$mouseX, $mouseY];
    }

    // ===============================================
    // Init
    // ===============================================

    private function setProps(): void
    {
        // allow the window to be resized by the user
        glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);
    }

    private function create(): void
    {
        $this->windowRef = glfwCreateWindow(
            $this->width,
            $this->height,
            $this->title,
        );

        if (! $this->windowRef) {
            throw new Exception('OS Window could not be initialized!');
        }
    }

    // ===============================================
    // Destroy
    // ===============================================

    public function __destruct()
    {
        glfwDestroyWindow($this->windowRef);
    }
}
