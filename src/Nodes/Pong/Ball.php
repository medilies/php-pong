<?php

namespace Medilies\TryingPhpGlfw\Nodes\Pong;

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Nodes\Node;
use Medilies\TryingPhpGlfw\Vertexes\BaseVertex;

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
        private BaseVertex $vertex,
    ) {
        $this->iPosX = $this->context->getCurrentWindowWidth() / 2;

        $this->iPosY = $this->context->getCurrentWindowHeight() / 2;

        $this->iMovAngle = random_int(0, 1) ? deg2rad(rand(45, 80)) : deg2rad(rand(100, 135));

        $this->iSpeed = $this->context->getCurrentWindowWidth() * 0.01;

        $this->reset();
        $this->start();
    }

    public function start(): void
    {
        $this->movAngle = $this->iMovAngle;
        $this->speed = $this->iSpeed;
    }

    public function reset(): void
    {
        $this->posX = $this->iPosX;
        $this->posY = $this->iPosY;

        $this->speed = 0;
    }

    public function act(): void
    {
        $this->move();

        $this->draw();
    }

    private function move(): void
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
            if ($this->context->getNode('pad')->collides($this->posX, $this->posY)) {
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
    }

    public function draw(): void
    {
        $model = new Mat4;

        // ! find a ratio
        $model->translate(new Vec3($this->posX, $this->posY));
        $model->scale(new Vec3(10, 10));

        $this->vertex->bind();
        $this->context->setUniform4f(U_MODEL, GL_FALSE, $model);
        $this->vertex->draw();
        $this->vertex->unbind();
    }
}
