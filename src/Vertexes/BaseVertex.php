<?php

namespace Medilies\TryingPhpGlfw\Vertexes;

abstract class BaseVertex
{
    protected readonly int $vao;

    protected readonly int $vbo;

    public function __construct()
    {
        [$this->vbo, $this->vao] = $this->generateAndBind();

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
     *
     * @return int[]
     */
    protected function generateAndBind(): array
    {
        glGenBuffers(1, $vbo);
        glGenVertexArrays(1, $vao);

        glBindBuffer(GL_ARRAY_BUFFER, $vbo);
        glBindVertexArray($vao);

        return [$vbo, $vao];
    }

    public function bind(): void
    {
        glBindVertexArray($this->vao);
    }

    public function unbind(): void
    {
        glBindBuffer(GL_ARRAY_BUFFER, 0);
        glBindVertexArray(0);
    }

    public function delete(): void
    {
        glDeleteVertexArrays(1, $this->vao);
        glDeleteBuffers(1, $this->vbo);
    }
}
