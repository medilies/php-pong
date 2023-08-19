<?php

require __DIR__.'/vendor/autoload.php';

const PROJECT_PATH = __DIR__;

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\PhpPong\OpenGl\Context;
use Medilies\PhpPong\Game\Nodes\Pong\Ball;
use Medilies\PhpPong\Game\Nodes\Pong\Pad;
use Medilies\PhpPong\OpenGl\ShaderProgram;
use Medilies\PhpPong\OpenGl\Vertexes\ElementarySquare;

$c = Context::make();
$c->createWindow(1080, 720, 'PONG');

$c->registerShaderProgram('pong', new ShaderProgram('pong', 'pong'));

$c->useShaderProgram('pong');

const U_MODEL = 'u_model';
const U_VIEW = 'u_view';
const U_PROJECTION = 'u_projection';

$c->registerUniformLocation('pong', U_MODEL);
$c->registerUniformLocation('pong', U_VIEW);
$c->registerUniformLocation('pong', U_PROJECTION);

$c->updateViewport();

$c->registerNode(new Pad($c, new ElementarySquare, 'pad'));
$c->registerNode(new Ball($c, new ElementarySquare, 'ball'));

// Scene
$view = new Mat4;
$view->translate(new Vec3()); // ! not needed
$projection = new Mat4;
$projection->ortho(
    0,
    $c->getCurrentWindowWidth(),
    0,
    $c->getCurrentWindowHeight(),
    -1,
    1
);

$c->setUniform4f(U_PROJECTION, false, $projection);
$c->setUniform4f(U_VIEW, false, $view);

$c->loop(function (Context $c) {
    glClear(GL_COLOR_BUFFER_BIT);
    glClearColor(0.8, 0.6, 0, 1);

    if ($c->isPressed(GLFW_KEY_ESCAPE)) {
        $c->closeCurrentWindow();
    }
});
