<?php

require __DIR__.'/../vendor/autoload.php';

use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\ShaderProgram;
use Medilies\TryingPhpGlfw\Vertexes\PongPad;

$c = Context::make();
$c->init();

$c->registerShaderProgram('pong', new ShaderProgram('pong', 'pong'));
$c->registerVertex('pad', new PongPad);

$c->useShaderProgram('pong');
$c->bindVertexArray('pad');

$c->updateViewport();

$c->loop(function (Context $c) {
    glClear(GL_COLOR_BUFFER_BIT);
    glClearColor(0.8, 0.6, 0, 1);

    $c->drawBoundedVertex();
});
