<?php

namespace Medilies\TryingPhpGlfw\Vertexes;

use GL\Buffer\FloatBuffer;

class ElementarySquare extends BaseVertex
{
    protected function setBufferData(): void
    {
        $top = 1.0;
        $right = 1.0;
        $bottom = 0.0;
        $left = 0.0;

        $topLeft = [$left, $top];
        $topRight = [$right, $top];
        $bottomRight = [$right, $bottom];
        $bottomLeft = [$left, $bottom];

        glBufferData(
            GL_ARRAY_BUFFER,
            new FloatBuffer([
                // positions     // colors
                ...$topRight,  0.0, 0.0, 0.6, // top right
                ...$bottomRight,  0.0, 0.0, 0.6, // bottom right
                ...$topLeft,  0.0, 0.0, 0.6, // top left

                ...$bottomRight,  0.0, 0.0, 0.6, // bottom right
                ...$bottomLeft,  0.0, 0.0, 0.6, // bottom left
                ...$topLeft,  0.0, 0.0, 0.6, // top left
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
