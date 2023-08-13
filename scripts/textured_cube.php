<?php

include __DIR__.'/../vendor/autoload.php';

use GL\Math\GLM;
use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\Vertexes\UvCube;
use Medilies\TryingPhpGlfw\ShaderProgram;
use Medilies\TryingPhpGlfw\TextureLoader;

$context = Context::make();
$context->init();

$shaderProgram = new ShaderProgram('cube', 'textured_cube');
$context->registerShaderProgram('textured_cube', $shaderProgram);

$context->registerVertex('uv_cube', new UvCube);

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
    //
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    //
    $context->useShaderProgram('textured_cube');

    //
    $model = new Mat4;
    //
    $speedMultiplier = 0.5;
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 1.0, 0.0));
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 0.0, 1.0));

    //
    $view = new Mat4;
    //
    $view->translate(new Vec3(0.0, 0.0, -2));

    //
    $projection = new Mat4;
    $projection->perspective(GLM::radians(70.0), 800 / 600, 0.1, 100.0);

    //
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

    //
    $context->bindVertexArray('uv_cube');
    $context->drawBoundedVertex();
});
