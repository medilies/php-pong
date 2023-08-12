<?php

use GL\Math\GLM;
use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Elements\UvCube;
use Medilies\TryingPhpGlfw\ShaderProgram;

require __DIR__.'/../vendor/autoload.php';

$context = Context::make();
$context->init();

$shaderProgram = new ShaderProgram('cube', 'cube');
$context->registerShaderProgram('cube', $shaderProgram);

$context->registerElement('uv_cube', new UvCube);

// update the viewport
glViewport(0, 0, 800, 600);

// enable depth testing, because we are rendering a 3d object with overlapping
// triangles
glEnable(GL_DEPTH_TEST);

$context->loop(function (Context $context) {
    glClearColor(0, 0, 0, 1);
    // note how we are clearing both the DEPTH and COLOR buffers.
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    // use the shader, will active the given shader program
    // for the coming draw calls.
    $context->useShaderProgram('cube');

    // ! Note:
    // Compared to triangle example, three new concepts are introduced here:
    // 1. Model
    // 2. View/Camera
    // 3. Perspective

    // define the model matrix aka the cube's position in the world
    $model = new Mat4;
    // because we want the cube to spin, we rotate the matrix based on the elapsed time.
    $speedMultiplier = 1;
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 1.0, 0.0));
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 0.0, 1.0));

    // next the view matrix, this is the camera / eye position and rotation
    $view = new Mat4;
    // you can imagine the camera is being moved back by 2 units here.
    $view->translate(new Vec3(-0.0, 0.0, -2));

    // and finally the projection matrix, this is the perspective matrix.
    $projection = new Mat4;
    $projection->perspective(GLM::radians(70.0), 800 / 600, 0.1, 100.0);

    // now set the uniform variables in the shader.
    // note that we use `glUniformMatrix4f` instead of `glUniformMatrix4fv` to pass a single matrix.
    glUniformMatrix4f(
        glGetUniformLocation($context->getShaderProgramRef('cube'), 'model'),
        GL_FALSE,
        $model
    );
    glUniformMatrix4f(
        glGetUniformLocation($context->getShaderProgramRef('cube'), 'view'),
        GL_FALSE,
        $view
    );
    glUniformMatrix4f(
        glGetUniformLocation($context->getShaderProgramRef('cube'), 'projection'),
        GL_FALSE,
        $projection
    );

    // bind & draw the vertex array
    $context->bindVertexArray('uv_cube');
    glDrawArrays(GL_TRIANGLES, 0, 36);
});
