<?php

namespace Medilies\TryingPhpGlfw\Nodes\Pong;

use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Nodes\Node;
use Medilies\TryingPhpGlfw\Vertexes\BaseVertex;

class Pad extends Node
{
    protected readonly float $iPosX;

    protected readonly float $iPosY;

    protected readonly float $iSpeed;

    public function __construct(
        protected Context $context,
        protected BaseVertex $vertex,
    ) {
        $this->iSpeed = $this->context->getCurrentWindowWidth() * 0.01;

        $this->width = 80;
        $this->heigh = 10;

        $this->iPosX = $this->context->getCurrentWindowWidth() / 2 - $this->width / 2;
        $this->iPosY = 20;

        $this->reset();
        $this->start();
    }

    public function start(): void
    {
        $this->speed = $this->iSpeed;
    }

    public function move(): void
    {
        $direction = 0;

        if ($this->context->isPressed(GLFW_KEY_LEFT)) {
            $direction = -1;
        }
        if ($this->context->isPressed(GLFW_KEY_RIGHT)) {
            $direction = 1;
        }

        $this->posX += $this->speed * $direction;

        if ($this->right() >= $this->context->getCurrentWindowWidth()) {
            $this->posX = $this->context->getCurrentWindowWidth() - $this->width;
        }

        if ($this->left() <= 0) {
            $this->posX = 0;
        }
    }

    public function postMove(): void
    {
        // ? change color if collided
    }
}
