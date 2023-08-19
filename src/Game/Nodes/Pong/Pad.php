<?php

namespace Medilies\PhpPong\Game\Nodes\Pong;

use Medilies\PhpPong\Game;
use Medilies\PhpPong\Game\Nodes\Node;
use Medilies\PhpPong\OpenGl\Context;
use Medilies\PhpPong\OpenGl\RectDrawer;
use Medilies\PhpPong\OpenGl\Vertexes\BaseVertex;

class Pad extends Node
{
    public function __construct(
        protected Context $context,
        protected BaseVertex $vertex,
        protected string $name,
        protected RectDrawer $drawer,
    ) {
        $this->iSpeed = Game::sceneWidth() * 0.01;

        $this->width = 80;
        $this->heigh = 10;

        $this->iPosX = Game::sceneWidth() / 2 - $this->width / 2;
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

        if ($this->context->isPressed(GLFW_KEY_LEFT)) { // TODO: change
            $this->movAngle = pi();
        } elseif ($this->context->isPressed(GLFW_KEY_RIGHT)) {
            $this->movAngle = 0;
        } else {
            $this->speed = 0;

            return;
        }

        $this->posX += cos($this->movAngle) * $this->speed;
        $this->posY += sin($this->movAngle) * $this->speed;

        if ($this->right() >= Game::sceneWidth()) {
            $this->posX = Game::sceneWidth() - $this->width;
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
