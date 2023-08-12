<?php

use Medilies\TryingPhpGlfw\Context;

include './vendor/autoload.php';

$context = Context::make();

$context->init();

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
});
