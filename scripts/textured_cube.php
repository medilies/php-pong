<?php

include __DIR__.'/../vendor/autoload.php';

use GL\Math\GLM;
use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\ShaderProgram;
use Medilies\TryingPhpGlfw\TextureLoader;
use Medilies\TryingPhpGlfw\Vertexes\UvCube;

$context = Context::make();
$context->init();

$shaderProgram = new ShaderProgram('cube', 'textured_cube');
$context->registerShaderProgram('textured_cube', $shaderProgram);

$context->registerUniformLocation('textured_cube', 'model');
$context->registerUniformLocation('textured_cube', 'view');
$context->registerUniformLocation('textured_cube', 'projection');

$context->registerVertex('uv_cube', new UvCube);

// ! --------------
TextureLoader::load(__DIR__.'/../src/assets/images/php-logo.png');
// ? idk how the state knows which texture to set for logo uniform
$context->registerUniformLocation('textured_cube', 'logo');
$context->setUniform1i('logo', 0);

// ! --------------

$context->updateViewport();

glEnable(GL_DEPTH_TEST);

$context->useShaderProgram('textured_cube');
$context->bindVertexArray('uv_cube');

$context->loop(function (Context $context) {
    glClearColor(0, 0, 0, 1);
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    $model = new Mat4;
    $view = new Mat4;
    $projection = new Mat4;

    $speedMultiplier = 0.5;
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 1.0, 0.0));
    $model->rotate(glfwGetTime() * $speedMultiplier, new Vec3(0.0, 0.0, 1.0));

    $view->translate(new Vec3(0.0, 0.0, -2));

    $projection->perspective(GLM::radians(70.0), 800 / 600, 0.1, 100.0);

    $context->setUniform4f('model', GL_FALSE, $model);
    $context->setUniform4f('view', GL_FALSE, $view);
    $context->setUniform4f('projection', GL_FALSE, $projection);

    $context->drawBoundedVertex();
});
