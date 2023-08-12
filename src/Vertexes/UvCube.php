<?php

namespace Medilies\TryingPhpGlfw\Vertexes;

use GL\Buffer\FloatBuffer;

class UvCube extends BaseVertex
{
    public function __construct()
    {
        $this->generateAndBind();

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
        // TODO: abstract behavior when having position, position+color ...
        $indexPosition = 0;
        $indexColor = 1;
        $positionSize = 3;
        $colorSize = 2;

        // positions
        glVertexAttribPointer(
            $indexPosition,
            $positionSize,
            GL_FLOAT,
            GL_FALSE,
            GL_SIZEOF_FLOAT * ($positionSize + $colorSize),
            0
        );
        glEnableVertexAttribArray($indexPosition);

        // color
        glVertexAttribPointer(
            $indexColor,
            $colorSize,
            GL_FLOAT,
            GL_FALSE,
            GL_SIZEOF_FLOAT * ($positionSize + $colorSize),
            GL_SIZEOF_FLOAT * $positionSize
        );
        glEnableVertexAttribArray($indexColor);

        $this->unbind();
    }
}
