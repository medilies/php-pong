<?php

namespace Medilies\TryingPhpGlfw;

use Exception;
use GLFWwindow;

class Window
{
    public GLFWwindow $ref;

    public function __construct()
    {
        $this->setProps()
            ->create();
    }

    private function setProps(): static
    {
        // allow the window to be resized by the user
        glfwWindowHint(GLFW_RESIZABLE, GL_TRUE);

        // set the OpenGL context version and profile 
        glfwWindowHint(GLFW_CONTEXT_VERSION_MAJOR, 4);
        glfwWindowHint(GLFW_CONTEXT_VERSION_MINOR, 1);
        glfwWindowHint(GLFW_OPENGL_PROFILE, GLFW_OPENGL_CORE_PROFILE);

        // enable forward compatibility, @see glfw docs for details
        glfwWindowHint(GLFW_OPENGL_FORWARD_COMPAT, GL_TRUE);

        return $this;
    }

    private function create(): static
    {
        if (!$this->ref = glfwCreateWindow(800, 600, "PHP GLFW Demo")) {
            throw new Exception('OS Window could not be initialized!');
        }

        return $this;
    }

    public function __destruct()
    {
        glfwDestroyWindow($this->ref);
    }
}
