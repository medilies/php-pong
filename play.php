<?php

require __DIR__.'/vendor/autoload.php';

use Medilies\PhpPong\Game;
use Medilies\PhpPong\Game\Nodes\Pong\Ball;
use Medilies\PhpPong\Game\Nodes\Pong\Pad;
use Medilies\PhpPong\OpenGl\Context;
use Medilies\PhpPong\OpenGl\Vertexes\ElementarySquare;

const PROJECT_PATH = __DIR__;

const U_MODEL = 'u_model';
const U_VIEW = 'u_view';
const U_PROJECTION = 'u_projection';

$game = Game::make();

$c = Context::make();

$c->init();

$game->registerNode(new Pad($c, new ElementarySquare, 'pad'));
$game->registerNode(new Ball($c, new ElementarySquare, 'ball'));

$game->loop(function (Game $game) {
    glClear(GL_COLOR_BUFFER_BIT);
    glClearColor(0.8, 0.6, 0, 1);

    if ($game->context->isPressed(GLFW_KEY_ESCAPE)) {
        $game->context->closeCurrentWindow();
    }
});
