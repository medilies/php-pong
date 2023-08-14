<?php

namespace Medilies\TryingPhpGlfw\Vertexes;

use GL\Buffer\FloatBuffer;

class PongPad extends BaseVertex
{
    protected function setBufferData(): void
    {
        glBufferData(
            GL_ARRAY_BUFFER,
            new FloatBuffer([
                // positions     // colors
                 0.5,  0.1,  0.0, 0.0, 0.6,// top right
                 0.5, -0.1,  0.0, 0.0, 0.6,// bottom right
                -0.5,  0.1,  0.0, 0.0, 0.6,// top left

                 0.5, -0.1,  0.0, 0.0, 0.6,// bottom right
                -0.5, -0.1,  0.0, 0.0, 0.6,// bottom left
                -0.5,  0.1,  0.0, 0.0, 0.6,// top left
            ]),
            GL_STATIC_DRAW
        );
    }

    protected function setAttributesLayout(): void
    {
        $indexPosition = 0;
        $indexColor = 1;
        $positionSize = 2;
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
    }

    public function draw(): void
    {
        glDrawArrays(GL_TRIANGLES, 0, 6); // TODO: automate count
    }
}
