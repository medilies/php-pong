<?php

namespace Medilies\TryingPhpGlfw\Elements;

abstract class Element
{
    protected readonly mixed $VAO;

    protected readonly mixed $VBO;

    abstract public function __construct();

    protected function createVertexArray(): void
    {
        // create a vertex array (VertexArrayObject -> VAO)
        glGenVertexArrays(1, $VAO);

        $this->VAO = $VAO;
    }

    protected function createVertexBuffer(): void
    {
        // create a buffer for our vertices (VertexBufferObject -> VBO)
        glGenBuffers(1, $VBO);

        $this->VBO = $VBO;
    }

    public function bind()
    {
        glBindVertexArray($this->VAO);
    }

    public function delete(): void
    {
        glDeleteVertexArrays(1, $this->VAO);
        glDeleteBuffers(1, $this->VBO);
    }
}
