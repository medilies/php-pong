<?php

namespace Medilies\TryingPhpGlfw\Elements;

use GL\Buffer\FloatBuffer;

class UvCube extends Element
{
    public function __construct()
    {
        $this->createVertexArray();
        $this->createVertexBuffer();

        glBindVertexArray($this->VAO); // * common
        glBindBuffer(GL_ARRAY_BUFFER, $this->VBO); // * common

        // create a floating point buffer with the vertex and uv data
        // of a cube with a 1x1x1 size.
        $vertices = new FloatBuffer([
            -0.5, -0.5, -0.5,  0.0, 0.0,
            0.5, -0.5, -0.5,  1.0, 0.0,
            0.5,  0.5, -0.5,  1.0, 1.0,
            0.5,  0.5, -0.5,  1.0, 1.0,
            -0.5,  0.5, -0.5,  0.0, 1.0,
            -0.5, -0.5, -0.5,  0.0, 0.0,

            -0.5, -0.5,  0.5,  0.0, 0.0,
            0.5, -0.5,  0.5,  1.0, 0.0,
            0.5,  0.5,  0.5,  1.0, 1.0,
            0.5,  0.5,  0.5,  1.0, 1.0,
            -0.5,  0.5,  0.5,  0.0, 1.0,
            -0.5, -0.5,  0.5,  0.0, 0.0,

            -0.5,  0.5,  0.5,  1.0, 0.0,
            -0.5,  0.5, -0.5,  1.0, 1.0,
            -0.5, -0.5, -0.5,  0.0, 1.0,
            -0.5, -0.5, -0.5,  0.0, 1.0,
            -0.5, -0.5,  0.5,  0.0, 0.0,
            -0.5,  0.5,  0.5,  1.0, 0.0,

            0.5,  0.5,  0.5,  1.0, 0.0,
            0.5,  0.5, -0.5,  1.0, 1.0,
            0.5, -0.5, -0.5,  0.0, 1.0,
            0.5, -0.5, -0.5,  0.0, 1.0,
            0.5, -0.5,  0.5,  0.0, 0.0,
            0.5,  0.5,  0.5,  1.0, 0.0,

            -0.5, -0.5, -0.5,  0.0, 1.0,
            0.5, -0.5, -0.5,  1.0, 1.0,
            0.5, -0.5,  0.5,  1.0, 0.0,
            0.5, -0.5,  0.5,  1.0, 0.0,
            -0.5, -0.5,  0.5,  0.0, 0.0,
            -0.5, -0.5, -0.5,  0.0, 1.0,

            -0.5,  0.5, -0.5,  0.0, 1.0,
            0.5,  0.5, -0.5,  1.0, 1.0,
            0.5,  0.5,  0.5,  1.0, 0.0,
            0.5,  0.5,  0.5,  1.0, 0.0,
            -0.5,  0.5,  0.5,  0.0, 0.0,
            -0.5,  0.5, -0.5,  0.0, 1.0,
        ]);

        glBufferData(GL_ARRAY_BUFFER, $vertices, GL_STATIC_DRAW);

        // declare the vertex attributes
        // positions
        glVertexAttribPointer(0, 3, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 5, 0);
        glEnableVertexAttribArray(0); // * common

        // uv
        glVertexAttribPointer(1, 2, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 5, GL_SIZEOF_FLOAT * 3);
        glEnableVertexAttribArray(1); // * common

        // ! the only unique parts are vertices buffer value and (positions,color) pointers definitions

        // unbind
        glBindBuffer(GL_ARRAY_BUFFER, 0); // * common
        glBindVertexArray(0); // * common
    }
}
