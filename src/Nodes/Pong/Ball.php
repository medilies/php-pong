<?php

namespace Medilies\TryingPhpGlfw\Nodes\Pong;

use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Nodes\Node;
use Medilies\TryingPhpGlfw\Vertexes\BaseVertex;

class Ball extends Node
{
    protected readonly float $iPosX;

    protected readonly float $iPosY;

    protected readonly float $iMovAngle;

    protected float $movAngle;

    protected readonly float $iSpeed;

    public function __construct(
        protected Context $context,
        protected BaseVertex $vertex,
    ) {
        $this->iPosX = $this->context->getCurrentWindowWidth() / 2;

        $this->iPosY = $this->context->getCurrentWindowHeight() / 2;

        $this->iMovAngle = random_int(0, 1) ? deg2rad(rand(45, 80)) : deg2rad(rand(100, 135));

        $this->iSpeed = $this->context->getCurrentWindowWidth() * 0.01;

        $this->width = 10;
        $this->heigh = 10;

        $this->reset();
        $this->start();
    }

    public function start(): void
    {
        $this->movAngle = $this->iMovAngle;
        $this->speed = $this->iSpeed;
    }

    public function move(): void
    {
        $this->newPos();
        $this->handleWallsCollisions();

        $this->baseAngle();
    }

    public function postMove(): void
    {
        // TODO: id should be a prop
        $collisions = $this->context->getCollisions('ball');

        // TODO: don't ask by name to define behavior
        if (isset($collisions['pad'])) {
            $this->movAngle = -$this->movAngle;
        }

        $this->handleLoss();
    }

    private function newPos(): void
    {
        $this->posX += cos($this->movAngle) * $this->speed;
        $this->posY += sin($this->movAngle) * $this->speed;
    }

    private function handleWallsCollisions(): void
    {
        if ($this->right() >= $this->context->getCurrentWindowWidth()) {
            $this->posX = $this->context->getCurrentWindowWidth() - $this->width;
            $this->movAngle = pi() - $this->movAngle;
        }

        if ($this->left() <= 0) {
            $this->posX = 0;
            $this->movAngle = pi() - $this->movAngle;
        }

        if ($this->top() >= $this->context->getCurrentWindowHeight()) {
            $this->posY = $this->context->getCurrentWindowHeight() - $this->heigh;
            $this->movAngle = -$this->movAngle;
        }
    }

    private function baseAngle(): void
    {
        if ($this->movAngle < 0) {
            $this->movAngle += 2 * pi();
        } elseif ($this->movAngle >= 2 * pi()) {
            $this->movAngle -= 2 * pi();
        }
    }

    private function handleLoss(): void
    {
        // TODO: signal it tso context
        if ($this->bottom() <= 0) {
            $this->posY = 0;
            $this->speed = 0;
        }
    }
}
