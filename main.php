<?php

use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\ShaderProgram;

include './vendor/autoload.php';

$context = Context::make();

$context->init();

// Shader Setup
// ----------------------------------------------------------------------------

$shaderProgram = new ShaderProgram('triangle', 'triangle');
$context->registerShaderProgram('triangle', $shaderProgram);

// Buffer and data setup
// ----------------------------------------------------------------------------

// create a vertex array (VertextArrayObject -> VAO)
glGenVertexArrays(1, $VAO);

// create a buffer for our vertices (VertextBufferObject -> VBO)
glGenBuffers(1, $VBO);

// bind the buffer to our VAO
glBindVertexArray($VAO);
glBindBuffer(GL_ARRAY_BUFFER, $VBO);
$context->registerVaoVbo('triangle', $VAO, $VBO);

// declare vertices for a single triangle and the colors for each vertex
$buffer = new \GL\Buffer\FloatBuffer([
    // positions     // colors
    0.5, -0.5, 0.0,  1.0, 0.0, 0.0,  // bottom right
    -0.5, -0.5, 0.0,  0.0, 1.0, 0.0,  // bottom let
    0.0,  0.5, 0.0,  0.0, 0.0, 1.0,   // top
]);

// now we can upload our float buffer to the currently bound VBO
glBufferData(GL_ARRAY_BUFFER, $buffer, GL_STATIC_DRAW);

// in the next step we have to define the vertex attributes, in simpler
// words tell openGL how the data we just uploaded should be split and iterated over.

// positions
glVertexAttribPointer(0, 3, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 6, 0);
glEnableVertexAttribArray(0);

// colors
glVertexAttribPointer(1, 3, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 6, GL_SIZEOF_FLOAT * 3);
glEnableVertexAttribArray(1);

// unbind
glBindBuffer(GL_ARRAY_BUFFER, 0);
glBindVertexArray(0);

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
