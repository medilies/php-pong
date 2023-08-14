<?php

require __DIR__.'/../vendor/autoload.php';

use GL\Math\GLM;
use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\ShaderProgram;
use Medilies\TryingPhpGlfw\Vertexes\PongPad;

$c = Context::make();
$c->init();

$c->registerShaderProgram('pong', new ShaderProgram('pong', 'pong'));
$c->registerVertex('pad', new PongPad);

$c->useShaderProgram('pong');

$c->registerUniformLocation('pong', 'model');
$c->registerUniformLocation('pong', 'view');
$c->registerUniformLocation('pong', 'projection');

$c->bindVertexArray('pad');

$c->updateViewport();

$c->loop(function (Context $c) {
    glClear(GL_COLOR_BUFFER_BIT);
    glClearColor(0.8, 0.6, 0, 1);

    static $posX = 0;
    $z = 5;
    $direction = 0;
    $speed = 0.05;

    if ($c->isPressed(GLFW_KEY_ESCAPE)) {
        $c->closeCurrentWindow();
    }

    if ($c->isPressed(GLFW_KEY_LEFT)) {
        $direction = -1;
    }
    if ($c->isPressed(GLFW_KEY_RIGHT)) {
        $direction = 1;
    }

    $posX = $posX + $speed * $direction;
    if ($posX > 1 * $z) {
        $posX = 1 * $z;
    }
    if ($posX < -1 * $z) {
        $posX = -1 * $z;
    }

    // define the model matrix aka the cube's position in the world
    $model = new Mat4;
    // next the view matrix, this is the camera / eye position and rotation
    $view = new Mat4;
    // and finally the projection matrix, this is the perspective matrix.
    $projection = new Mat4;

    $model->translate(new Vec3($posX, 0.0, 0.0));

    $view->translate(new Vec3(0.0, 0.0, -$z));

    $projection->perspective(GLM::radians(70.0), 800 / 600, 0.1, 100.0);

    // now set the uniform variables in the shader.
    $c->setUniform4f('model', GL_FALSE, $model);
    $c->setUniform4f('view', GL_FALSE, $view);
    $c->setUniform4f('projection', GL_FALSE, $projection);

    $c->drawBoundedVertex();
});
