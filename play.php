<?php

require __DIR__.'/vendor/autoload.php';

use Medilies\PhpPong\Game;
use Medilies\PhpPong\Game\Nodes\Pong\Ball;
use Medilies\PhpPong\Game\Nodes\Pong\Pad;
use Medilies\PhpPong\OpenGl\Context;
use Medilies\PhpPong\OpenGl\Vertexes\ElementarySquare;

const PROJECT_PATH = __DIR__;

const SQUARE_SHADER = 'BasicMvp';

const U_MODEL = 'u_model';
const U_VIEW = 'u_view';
const U_PROJECTION = 'u_projection';

$c = Context::make();

$game = Game::make($c, 1080, 720);

$game->init();

$game->registerNode(new Pad($c, new ElementarySquare, 'pad'));
$game->registerNode(new Ball($c, new ElementarySquare, 'ball'));

$game->loop();
