<?php

namespace Medilies\PhpPong\Game\Nodes;

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\PhpPong\OpenGl\Context;
use Medilies\PhpPong\OpenGl\RectDrawer;
use Medilies\PhpPong\OpenGl\Vertexes\BaseVertex;

abstract class Node
{
    protected string $name;

    protected float $posX;

    protected float $posY;

    protected float $width;

    protected float $heigh;

    protected float $iPosX;

    protected float $iPosY;

    protected float $iSpeed;

    protected float $speed;

    protected float $movAngle;

    protected Context $context;

    protected BaseVertex $vertex;

    protected RectDrawer $drawer; // TODO: interface it

    public function reset(): void
    {
        $this->posX = $this->iPosX;
        $this->posY = $this->iPosY;

        $this->speed = 0;
    }

    abstract public function start(): void;

    abstract public function move(): void;

    abstract public function postMove(): void;

    public function draw(): void
    {

        $this->vertex->bind();

        $this->drawer->draw($this->posX, $this->posY, $this->width, $this->heigh);

        $this->vertex->draw();
        $this->vertex->unbind();
    }

    /**
     * Input node square boundaries
     *
     * Handles squares and rectangles
     */
    public function collided(Node $node): bool
    {
        // fully above
        if ($this->top() > $node->top() && $this->bottom() > $node->top()) {
            return false;
        }

        // fully under
        if ($this->bottom() < $node->bottom() && $this->top() < $node->bottom()) {
            return false;
        }

        // fully to the right
        if ($this->right() > $node->right() && $this->left() > $node->right()) {
            return false;
        }

        // fully to the left
        if ($this->left() < $node->left() && $this->right() < $node->left()) {
            return false;
        }

        return true;
    }

    public function top(): float
    {
        return $this->posY + $this->heigh;
    }

    public function right(): float
    {
        return $this->posX + $this->width;
    }

    public function bottom(): float
    {
        return $this->posY;
    }

    public function left(): float
    {
        return $this->posX;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
