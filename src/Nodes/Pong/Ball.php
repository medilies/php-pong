<?php

namespace Medilies\TryingPhpGlfw\Nodes\Pong;

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Nodes\Node;

class Ball extends Node
{
    private readonly float $iPosX;

    private float $posX;

    private readonly float $iPosY;

    private float $posY;

    private readonly float $iMovAngle;

    private float $movAngle;

    protected readonly float $iSpeed;
    protected float $speed;

    public function __construct(
        protected Context $context,
    ) {
        $this->iPosX = $this->context->getCurrentWindowWidth() / 2;
        $this->posX = $this->iPosX;

        $this->iPosY = $this->context->getCurrentWindowHeight() / 2;
        $this->posY = $this->iPosY;

        $this->iMovAngle = random_int(0, 1) ? deg2rad(rand(45, 80)) : deg2rad(rand(100, 135));
        $this->movAngle = $this->iMovAngle;

        $this->iSpeed = $this->context->getCurrentWindowWidth() * 0.01;
        $this->speed = $this->iSpeed;
    }

    public function act(): void
    {
        $this->posX = $this->posX + cos($this->movAngle) * $this->speed;
        $this->posY = $this->posY + sin($this->movAngle) * $this->speed;

        if ($this->posX >= $this->context->getCurrentWindowWidth()) {
            $this->posX = $this->context->getCurrentWindowWidth();
            $this->movAngle = pi() - $this->movAngle;
        }
        if ($this->posX <= 0) {
            $this->movAngle = pi() - $this->movAngle;
        }

        if ($this->posY >= $this->context->getCurrentWindowHeight()) {
            $this->posY = $this->context->getCurrentWindowHeight();
            $this->movAngle = -$this->movAngle;
        }
        if ($this->posY < 0) {
            if($this->context->getNode('pad')->collides($this->posX, $this->posY)){
                $this->movAngle = -$this->movAngle;
            } else {
                $this->posY = 0; // ! restart
                $this->speed = 0;
            }
        }

        if ($this->movAngle < 0) {
            $this->movAngle += 2 * pi();
        } elseif ($this->movAngle >= 2 * pi()) {
            $this->movAngle -= 2 * pi();
        }

        // define the model matrix aka the cube's position in the world
        $model = new Mat4;

        // ! find a ratio
        $model->translate(new Vec3($this->posX, $this->posY, 0.0));
        $model->scale(new Vec3(10, 10, 0));

        // now set the uniform variables in the shader.
        $this->context->setUniform4f(U_MODEL, GL_FALSE, $model);
    }
}
