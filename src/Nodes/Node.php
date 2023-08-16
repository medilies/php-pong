<?php

namespace Medilies\TryingPhpGlfw\Nodes;

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Vertexes\BaseVertex;

abstract class Node
{
    protected float $posX;

    protected float $posY;

    protected float $width;

    protected float $heigh;

    protected Context $context;

    protected BaseVertex $vertex;

    abstract public function reset();

    abstract public function start();

    abstract public function move();

    abstract public function postDraw();

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
     */
    public function collided(Node $node): bool
    {
        if (($node->left() + $node->width) < $this->left()) {
            return false;
        }

        if (($node->right() - $node->width) > $this->right()) {
            return false;
        }

        // ? top vs top
        return $node->bottom() >= $this->bottom() && $node->bottom() <= $this->top();
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
}
