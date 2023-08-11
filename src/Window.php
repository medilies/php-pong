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

    /**
     * Bind to current context
     */
    public function makeCurrentContext(): void
    {
        // This function makes the OpenGL or OpenGL ES context of the specified window current on the calling thread. A context must only be made current on a single thread at a time and each thread can have only a single current context at a time.
        // In other words all GL commands will be executed in the context of this window.
        // Special in PHP-GLFW is that this will also initialize GLAD.
        glfwMakeContextCurrent($this->windowRef);

        // Cannot do this before glfwMakeContextCurrent
        // ? related to GLAD
        echo glGetString(GL_VERSION).PHP_EOL;
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

    public function getRef(): GLFWwindow
    {
        return $this->windowRef;
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
