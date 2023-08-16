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

    abstract public function act();

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
}
