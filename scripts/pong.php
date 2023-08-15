<?php

require __DIR__.'/../vendor/autoload.php';

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Nodes\Pong\Ball;
use Medilies\TryingPhpGlfw\Nodes\Pong\Pad;
use Medilies\TryingPhpGlfw\ShaderProgram;
use Medilies\TryingPhpGlfw\Vertexes\PongPad;

$c = Context::make();
$c->createWindow(1080, 720, 'PONG');

$c->registerShaderProgram('pong', new ShaderProgram('pong', 'pong'));

$c->registerVertex('pad', new PongPad);
$c->registerVertex('ball', new PongPad);
// ! I need to be aware of screen size to set pad size

$c->useShaderProgram('pong');

const U_MODEL = 'u_model';
const U_VIEW = 'u_view';
const U_PROJECTION = 'u_projection';

$c->registerUniformLocation('pong', U_MODEL);
$c->registerUniformLocation('pong', U_VIEW);
$c->registerUniformLocation('pong', U_PROJECTION);

$c->updateViewport();

$c->registerNode('pad', new Pad($c));
$c->registerNode('ball', new Ball($c));

$c->loop(function (Context $c) {
    glClear(GL_COLOR_BUFFER_BIT);
    glClearColor(0.8, 0.6, 0, 1);

    if ($c->isPressed(GLFW_KEY_ESCAPE)) {
        $c->closeCurrentWindow();
    }

    // next the view matrix, this is the camera / eye position and rotation
    $view = new Mat4;
    // and finally the projection matrix, this is the perspective matrix.
    $projection = new Mat4;

    $view->translate(new Vec3(0.0, 0.0, 0.0)); // ! not needed

    $projection->ortho(
        0,
        $c->getCurrentWindowWidth(),
        0,
        $c->getCurrentWindowHeight(),
        0,
        100
    );

    $c->setUniform4f(U_PROJECTION, GL_FALSE, $projection);
    $c->setUniform4f(U_VIEW, GL_FALSE, $view);

    $c->bindVertexArray('pad');
    $c->getNode('pad')->act();
    $c->drawBoundedVertex();
    $c->unbindVertexArray();

    $c->bindVertexArray('ball');
    $c->getNode('ball')->act();
    $c->drawBoundedVertex();
    $c->unbindVertexArray();
});
