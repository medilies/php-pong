<?php

namespace Medilies\TryingPhpGlfw\Nodes\Pong;

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Nodes\Node;

class Pad extends Node
{

    public function act()
    {
        static $posX = 1080 / 2;
        $direction = 0;
        $speed = $this->context->getCurrentWindowWidth() * 0.01;

        if ($this->context->isPressed(GLFW_KEY_LEFT)) {
            $direction = -1;
        }
        if ($this->context->isPressed(GLFW_KEY_RIGHT)) {
            $direction = 1;
        }

        $posX = $posX + $speed * $direction;
        // TODO: take into consideration pad size for boundaries
        if ($posX > $this->context->getCurrentWindowWidth()) {
            $posX = $this->context->getCurrentWindowWidth();
        }
        if ($posX < 0) {
            $posX = 0;
        }

        // define the model matrix aka the cube's position in the world
        $model = new Mat4;

        // ! find a ratio
        $model->translate(new Vec3($posX, 0.0, 0.0));
        $model->scale(new Vec3(80, 10, 0));

        // now set the uniform variables in the shader.
        $this->context->setUniform4f(U_MODEL, GL_FALSE, $model);
    }
}
