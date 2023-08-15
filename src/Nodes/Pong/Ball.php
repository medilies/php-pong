<?php

namespace Medilies\TryingPhpGlfw\Nodes\Pong;

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Nodes\Node;

class Ball extends Node
{
    public function act(): void
    {
        static $posX = 1080 / 2;
        static $posY = 720 / 2;
        static $angle = 45;
        $speed = $this->context->getCurrentWindowWidth() * 0.01;

        $speedX = cos($angle) * $speed;
        $speedY = sin($angle) * $speed;

        $posX += $speedX;
        $posY += $speedY;

        if ($posX > $this->context->getCurrentWindowWidth()) {
            $posX = $this->context->getCurrentWindowWidth();
        }
        if ($posX < 0) {
            $posX = 0; // ! lost
        }

        if ($posY > $this->context->getCurrentWindowHeight()) {
            $posY = $this->context->getCurrentWindowHeight();
        }
        if ($posY < 0) {
            $posY = 0; // ! lost
        }

        // define the model matrix aka the cube's position in the world
        $model = new Mat4;

        // ! find a ratio
        $model->translate(new Vec3($posX, $posY, 0.0));
        $model->scale(new Vec3(10, 10, 0));

        // now set the uniform variables in the shader.
        $this->context->setUniform4f(U_MODEL, GL_FALSE, $model);
    }
}
