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
    ) {
        $this->posY = 0;
        $this->width = 80;
        $this->heigh = 10;

        $this->reset();
        $this->start();
    }

    public function start(): void
    {
    }

    public function reset(): void
    {
        $this->posX = $this->context->getCurrentWindowWidth() / 2;
    }

    public function act(): void
    {
        $this->move();

        $this->draw();
    }

    private function move(): void
    {
        $direction = 0;
        $speed = $this->context->getCurrentWindowWidth() * 0.01;

        if ($this->context->isPressed(GLFW_KEY_LEFT)) {
            $direction = -1;
        }
        if ($this->context->isPressed(GLFW_KEY_RIGHT)) {
            $direction = 1;
        }

        $this->posX = $this->posX + $speed * $direction;
        // TODO: take into consideration pad size for boundaries
        if ($this->posX > $this->context->getCurrentWindowWidth()) {
            $this->posX = $this->context->getCurrentWindowWidth();
        }
        if ($this->posX < 0) {
            $this->posX = 0;
        }
    }

    public function collides($x, $y): bool
    {
        return $x > ($this->posX) && $x < ($this->posX + 80);
    }
}
