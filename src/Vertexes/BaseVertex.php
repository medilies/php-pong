<?php

namespace Medilies\TryingPhpGlfw\Vertexes;

// TODO: rename to Vertex
abstract class BaseVertex
{
    protected readonly mixed $VAO;

    protected readonly mixed $VBO;

    public function __construct()
    {
        $this->generateAndBind();

        $this->setBufferData();

        $this->setAttributesLayout();

        $this->unbind();
    }

    abstract protected function setBufferData(): void;

    abstract protected function setAttributesLayout(): void;

    abstract public function draw(): void;

    /**
     * create a vertex array (VertexArrayObject -> VAO)
     * create a buffer for our vertices (VertexBufferObject -> VBO)
     */
    protected function generateAndBind(): void
    {
        glGenBuffers(1, $this->VBO);
        glGenVertexArrays(1, $this->VAO);

        glBindBuffer(GL_ARRAY_BUFFER, $this->VBO);
        glBindVertexArray($this->VAO);
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
