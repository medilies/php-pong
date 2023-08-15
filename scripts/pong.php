<?php

require __DIR__.'/../vendor/autoload.php';

use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
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
    padControl($c);
    $c->drawBoundedVertex();
    $c->unbindVertexArray();

    $c->bindVertexArray('ball');
    ballControl($c);
    $c->drawBoundedVertex();
    $c->unbindVertexArray();
});

function padControl(Context $c): void
{
    static $posX = 1080 / 2;
    $direction = 0;
    $speed = $c->getCurrentWindowWidth() * 0.01;

    if ($c->isPressed(GLFW_KEY_LEFT)) {
        $direction = -1;
    }
    if ($c->isPressed(GLFW_KEY_RIGHT)) {
        $direction = 1;
    }

    $posX = $posX + $speed * $direction;
    // TODO: take into consideration pad size for boundaries
    if ($posX > $c->getCurrentWindowWidth()) {
        $posX = $c->getCurrentWindowWidth();
    }
    if ($posX < 0) {
        $posX = 0;
    }

    // define the model matrix aka the cube's position in the world
    $model = new Mat4;

    // ! find a ratio
    $model->translate(new Vec3($posX, 0.0, 0.0));
    $model->scale(new Vec3(80, 10, 0));

    // now set the uniform variables in the shader.
    $c->setUniform4f(U_MODEL, GL_FALSE, $model);
}

function ballControl(Context $c): void
{
    static $posX = 1080 / 2;
    static $posY = 720 / 2;
    static $angle = 45;
    $speed = $c->getCurrentWindowWidth() * 0.01;

    $speedX = cos($angle) * $speed;
    $speedY = sin($angle) * $speed;

    $posX += $speedX;
    $posY += $speedY;

    if ($posX > $c->getCurrentWindowWidth()) {
        $posX = $c->getCurrentWindowWidth();
    }
    if ($posX < 0) {
        $posX = 0; // ! lost
    }

    if ($posY > $c->getCurrentWindowHeight()) {
        $posY = $c->getCurrentWindowHeight();
    }
    if ($posY < 0) {
        $posY = 0; // ! lost
    }

    // define the model matrix aka the cube's position in the world
    $model = new Mat4;

    // ! find a ratio
    $model->translate(new Vec3($posX, $posY, 0.0));
    $model->scale(new Vec3(10, 10, 0));

    // now set the uniform variables in the shader.
    $c->setUniform4f(U_MODEL, GL_FALSE, $model);
}
