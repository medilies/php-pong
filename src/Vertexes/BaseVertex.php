<?php

namespace Medilies\TryingPhpGlfw\Vertexes;

// TODO: rename to Vertex
abstract class BaseVertex
{
    protected readonly mixed $VAO;

    protected readonly mixed $VBO;

    abstract public function __construct();

    abstract protected function setBufferData(): void;

    /**
     * create a vertex array (VertexArrayObject -> VAO)
     * create a buffer for our vertices (VertexBufferObject -> VBO)
     */
    protected function generateAndBind(): void
    {
        glGenVertexArrays(1, $this->VAO);
        glGenBuffers(1, $this->VBO);

        glBindVertexArray($this->VAO);
        glBindBuffer(GL_ARRAY_BUFFER, $this->VBO);
    }

    public function bind()
    {
        glBindVertexArray($this->VAO);
    }

    public function unbind()
    {
        glBindBuffer(GL_ARRAY_BUFFER, 0);
        glBindVertexArray(0);
    }

    public function delete(): void
    {
        glDeleteVertexArrays(1, $this->VAO);
        glDeleteBuffers(1, $this->VBO);
    }
}
