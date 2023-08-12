<?php

use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Elements\PlainTriangle;
use Medilies\TryingPhpGlfw\ShaderProgram;

include './vendor/autoload.php';

$context = Context::make();

$context->init();

// ----------------------------------------------------------------------------
// Register shaders and elements

$shaderProgram = new ShaderProgram('triangle', 'triangle');
$context->registerShaderProgram('triangle', $shaderProgram);

$element = new PlainTriangle;
$context->registerElement('triangle', $element);

// ----------------------------------------------------------------------------

$context->loop(function (Context $context) {
    // Close input event
    if (glfwGetKey($context->getCurrentWindow()->getRef(), GLFW_KEY_ESCAPE) == GLFW_PRESS) {
        glfwSetWindowShouldClose($context->getCurrentWindow()->getRef(), GL_TRUE);
    }

    // setting the clear color to black and clearing the color buffer
    [$mouseX, $mouseY] = $context->getCurrentWindow()->getCursorPos();

    // Render
    glClearColor(sin($mouseX / 300), sin($mouseY / 300), cos($mouseY / 300), 1);
    glClear(GL_COLOR_BUFFER_BIT);

    // use the shader, will active the given shader program
    // for the coming draw calls.
    $context->useShaderProgramIfExists('triangle');

    // bind & draw the vertex array
    $context->bindVertexArray('triangle');
    glDrawArrays(GL_TRIANGLES, 0, 3);
});
