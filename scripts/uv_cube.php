<?php

use GL\Math\GLM;
use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\ShaderProgram;
use Medilies\TryingPhpGlfw\Vertexes\UvCube;

require __DIR__.'/../vendor/autoload.php';

$context = Context::make();
$context->init();

$shaderProgram = new ShaderProgram('cube', 'cube');
$context->registerShaderProgram('cube', $shaderProgram);

// ? bind program
$context->registerUniformLocation('cube', 'model');
$context->registerUniformLocation('cube', 'view');
$context->registerUniformLocation('cube', 'projection');
// ? unbind program

$context->registerVertex('uv_cube', new UvCube);

$context->updateViewport();

// enable depth testing, because we are rendering a 3d object with overlapping triangles
glEnable(GL_DEPTH_TEST);

$context->useShaderProgram('cube');
$context->bindVertexArray('uv_cube');

$context->loop(function (Context $context) {
    glClearColor(0, 0, 0, 1);
    // note how we are clearing both the DEPTH and COLOR buffers.
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    setUniforms($context);

    $context->drawBoundedVertex();
});

function setUniforms(Context $context)
{
    // define the model matrix aka the cube's position in the world
    $model = new Mat4;
    // next the view matrix, this is the camera / eye position and rotation
    $view = new Mat4;
    // and finally the projection matrix, this is the perspective matrix.
    $projection = new Mat4;

    // because we want the cube to spin, we rotate the matrix based on the elapsed time.
    $speedMultiplier = 1;
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 1.0, 0.0));
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 0.0, 1.0));

    // you can imagine the camera is being moved back by 2 units here.
    $view->translate(new Vec3(-0.0, 0.0, -2));

    $projection->perspective(GLM::radians(70.0), 800 / 600, 0.1, 100.0);

    // now set the uniform variables in the shader.
    $context->setUniform4f('model', GL_FALSE, $model);
    $context->setUniform4f('view', GL_FALSE, $view);
    $context->setUniform4f('projection', GL_FALSE, $projection);
}
