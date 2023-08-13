<?php

namespace Medilies\TryingPhpGlfw\Vertexes;

use GL\Buffer\FloatBuffer;

class UvCube extends BaseVertex
{
    protected function setBufferData(): void
    {
        // floats buffer with the vertex and uv data of a cube with a 1x1x1 size.

        glBufferData(
            GL_ARRAY_BUFFER,
            new FloatBuffer([
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
            ]),
            GL_STATIC_DRAW
        );
    }

    protected function setAttributesLayout(): void
    {
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
    }

    public function draw(): void
    {
        glDrawArrays(GL_TRIANGLES, 0, 36);
    }
}
