<?php

namespace Medilies\TryingPhpGlfw\Elements;

class PlainTriangle extends Element
{
    public function __construct()
    {
        $this->createVertexArray();
        $this->createVertexBuffer();

        // bind the buffer to our VAO
        glBindVertexArray($this->VAO);
        glBindBuffer(GL_ARRAY_BUFFER, $this->VBO);

        // declare vertices for a single triangle and the colors for each vertex
        $buffer = new \GL\Buffer\FloatBuffer([
            // positions     // colors
            0.5, -0.5, 0.0,  1.0, 0.0, 0.0,  // bottom right
            -0.5, -0.5, 0.0,  0.0, 1.0, 0.0,  // bottom let
            0.0,  0.5, 0.0,  0.0, 0.0, 1.0,   // top
        ]);

        // now we can upload our float buffer to the currently bound VBO
        glBufferData(GL_ARRAY_BUFFER, $buffer, GL_STATIC_DRAW);

        // in the next step we have to define the vertex attributes, in simpler
        // words tell openGL how the data we just uploaded should be split and iterated over.

        // positions
        glVertexAttribPointer(0, 3, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 6, 0);
        glEnableVertexAttribArray(0);

        // colors
        glVertexAttribPointer(1, 3, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 6, GL_SIZEOF_FLOAT * 3);
        glEnableVertexAttribArray(1);

        // unbind
        glBindBuffer(GL_ARRAY_BUFFER, 0);
        glBindVertexArray(0);
    }
}
