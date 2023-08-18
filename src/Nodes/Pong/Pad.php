<?php

namespace Medilies\TryingPhpGlfw\Nodes\Pong;

use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Nodes\Node;
use Medilies\TryingPhpGlfw\Vertexes\BaseVertex;

class Pad extends Node
{
    public function __construct(
        protected Context $context,
        protected BaseVertex $vertex,
        protected string $name,
    ) {
        $this->iSpeed = $this->context->getCurrentWindowWidth() * 0.01;

        $this->width = 80;
        $this->heigh = 10;

        $this->iPosX = $this->context->getCurrentWindowWidth() / 2 - $this->width / 2;
        $this->iPosY = 20;

        $this->reset();
    }

    public function start(): void
    {
        $this->speed = $this->iSpeed;
    }

    public function move(): void
    {
        $this->speed = $this->iSpeed;

        if ($this->context->isPressed(GLFW_KEY_LEFT)) {
            $this->movAngle = pi();
        } elseif ($this->context->isPressed(GLFW_KEY_RIGHT)) {
            $this->movAngle = 0;
        } else {
            $this->speed = 0;

            return;
        }

        $this->posX += cos($this->movAngle) * $this->speed;
        $this->posY += sin($this->movAngle) * $this->speed;

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
