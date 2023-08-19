<?php

namespace Medilies\PhpPong\OpenGl;

use GL\Math\Mat4;
use GL\Math\Vec3;

class RectDrawer
{
    public function draw(float $posX, float $posY, float $width, float $heigh): void
    {
        $model = new Mat4;
        $model->translate(new Vec3($posX, $posY));
        $model->scale(new Vec3($width, $heigh));
        Context::setUniform4f(U_MODEL, false, $model);
    }
}
