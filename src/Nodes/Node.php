<?php

namespace Medilies\TryingPhpGlfw\Nodes;

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Vertexes\BaseVertex;

abstract class Node
{
    protected string $name;

    protected float $posX;

    protected float $posY;

    protected float $width;

    protected float $heigh;

    protected readonly float $iPosX;

    protected readonly float $iPosY;

    protected readonly float $iSpeed;

    protected float $speed;

    protected float $movAngle;

    protected Context $context;

    protected BaseVertex $vertex;

    public function reset(): void
    {
        $this->posX = $this->iPosX;
        $this->posY = $this->iPosY;

        $this->speed = 0;
    }

    abstract public function start();

    abstract public function move();

    abstract public function postMove();

    public function draw(): void
    {
        $model = new Mat4;

        // ! find a ratio
        $model->translate(new Vec3($this->posX, $this->posY));
        $model->scale(new Vec3($this->width, $this->heigh));

        $this->vertex->bind();
        $this->context->setUniform4f(U_MODEL, GL_FALSE, $model);
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
