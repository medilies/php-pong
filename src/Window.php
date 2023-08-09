<?php

namespace Medilies\TryingPhpGlfw;

use Exception;
use GLFWwindow;

class Window
{
    private GLFWwindow $windowRef;

    public function __construct(
        private int $width = 800,
        private int $height = 600,
        private string $title = 'PHP GLFW Demo',
    ) {
        $this->setProps();
        $this->create();
        glfwMakeContextCurrent($this->windowRef);
    }

    // ===============================================
    // ...
    // ===============================================

    public function shouldClose(): bool
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

        // set the OpenGL context version and profile
        glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, 4);
        glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, 1);
        glfwWindowHint(GLFW_OPENGL_PROFILE, GLFW_OPENGL_CORE_PROFILE);

        // enable forward compatibility, @see glfw docs for details
        glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);
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
