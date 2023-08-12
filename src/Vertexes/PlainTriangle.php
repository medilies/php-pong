<?php

namespace Medilies\TryingPhpGlfw\Vertexes;

use GL\Buffer\FloatBuffer;

class PlainTriangle extends BaseVertex
{
    public function __construct()
    {
        $this->generateAndBind();

        // declare vertices for a single triangle and the colors for each vertex
        $buffer = new FloatBuffer([
            // positions     // colors
            0.5, -0.5, 0.0,  1.0, 0.0, 0.0,  // bottom right
            -0.5, -0.5, 0.0,  0.0, 1.0, 0.0,  // bottom let
            0.0,  0.5, 0.0,  0.0, 0.0, 1.0,   // top
        ]);

        // now we can upload our float buffer to the currently bound VBO
        glBufferData(GL_ARRAY_BUFFER, $buffer, GL_STATIC_DRAW);

        // in the next step we have to define the vertex attributes, in simpler
        // words tell openGL how the data we just uploaded should be split and iterated over.

        // declare the vertex attributes
        $indexPosition = 0;
        $indexColor = 1;
        $positionSize = 3;
        $colorSize = 3;

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
