<?php

use GL\Math\GLM;
use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Elements\UvCube;
use Medilies\TryingPhpGlfw\ShaderProgram;
use Medilies\TryingPhpGlfw\TextureLoader;

include __DIR__.'/../vendor/autoload.php';

$context = Context::make();
$context->init();

$shaderProgram = new ShaderProgram('cube', 'textured_cube');
$context->registerShaderProgram('textured_cube', $shaderProgram);

$context->registerElement('uv_cube', new UvCube);

// ! --------------
// This is the same code for uv_cube except
// 1. The usage of a different fragment shader
// 2. Texture loading

TextureLoader::load(__DIR__.'/../src/assets/images/php-logo.png');

// set the shader uniform to point the texture unit to our texture
$context->useShaderProgram('textured_cube'); // ? hooked texture to shader program
glUniform1i(
    glGetUniformLocation($shaderProgram->getRef(), 'logo'),
    0
);

// ! --------------

// update the viewport
glViewport(0, 0, 800, 600);

// enable depth testing, because we are rendering a 3d object with overlapping triangles
glEnable(GL_DEPTH_TEST);

$context->loop(function (Context $context) {
    glClearColor(0, 0, 0, 1);
    // note how we are clearing both the DEPTH and COLOR buffers.
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    // use the shader, will active the given shader program
    // for the coming draw calls.
    $context->useShaderProgram('textured_cube');

    // define the model matrix aka the cubes position in the world
    $model = new Mat4;
    // because we want the cube to spin, we rotate the matrix based
    // on the elapsed time.
    $speedMultiplier = 0.5;
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 1.0, 0.0));
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 0.0, 1.0));

    // next the view matrix, this is the camera / eye position and rotation
    $view = new Mat4;
    // you can imagine the camera is being moved back by 2 units here.
    $view->translate(new Vec3(0.0, 0.0, -2));

    // and finally the projection matrix, this is the perspective matrix.
    $projection = new Mat4;
    $projection->perspective(GLM::radians(70.0), 800 / 600, 0.1, 100.0);

    // now set the uniform variables in the shader.
    // note that we use `glUniformMatrix4f` instead of `glUniformMatrix4fv` to pass a single matrix.
    glUniformMatrix4f(
        glGetUniformLocation($context->getShaderProgramRef('textured_cube'), 'model'),
        GL_FALSE,
        $model
    );
    glUniformMatrix4f(
        glGetUniformLocation($context->getShaderProgramRef('textured_cube'), 'view'),
        GL_FALSE,
        $view
    );
    glUniformMatrix4f(
        glGetUniformLocation($context->getShaderProgramRef('textured_cube'), 'projection'),
        GL_FALSE,
        $projection
    );

    // bind & draw the vertex array
    $context->bindVertexArray('uv_cube');
    glDrawArrays(GL_TRIANGLES, 0, 36);
});
