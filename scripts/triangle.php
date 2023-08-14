<?php

use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\ShaderProgram;
use Medilies\TryingPhpGlfw\Vertexes\PlainTriangle;

include __DIR__.'/../vendor/autoload.php';

$context = Context::make();

$context->createWindow(800, 600, 'TRIANGLE');

$shaderProgram = new ShaderProgram('triangle', 'triangle');
$context->registerShaderProgram('triangle', $shaderProgram);

$element = new PlainTriangle;
$context->registerVertex('triangle', $element);

$context->useShaderProgramIfExists('triangle');

$context->bindVertexArray('triangle');

$context->loop(function (Context $context) {
    if ($context->isPressed(GLFW_KEY_ESCAPE)) {
        $context->closeCurrentWindow();
    }

    // setting the clear color to black and clearing the color buffer
    [$mouseX, $mouseY] = $context->getCurrentWindow()->getCursorPos();

    // Render
    glClearColor(sin($mouseX / 300), sin($mouseY / 300), cos($mouseY / 300), 1);
    glClear(GL_COLOR_BUFFER_BIT);

    $context->drawBoundedVertex();
});
