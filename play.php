<?php

require __DIR__.'/vendor/autoload.php';

use Medilies\PhpPong\Game;
use Medilies\PhpPong\Game\Nodes\Pong\Ball;
use Medilies\PhpPong\Game\Nodes\Pong\Pad;
use Medilies\PhpPong\OpenGl\Context;
use Medilies\PhpPong\OpenGl\RectDrawer;
use Medilies\PhpPong\OpenGl\Vertexes\ElementarySquare;

const PROJECT_PATH = __DIR__;

const SQUARE_SHADER = 'BasicMvp';

const U_MODEL = 'u_model';
const U_VIEW = 'u_view';
const U_PROJECTION = 'u_projection';

$game = Game::make(Context::make(), 1080, 720);

$game->init();

$game->addNode(new Pad(new ElementarySquare, 'pad', new RectDrawer));
$game->addNode(new Ball(new ElementarySquare, 'ball', new RectDrawer));

$game->loop();
