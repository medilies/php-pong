<?php

include __DIR__.'/../vendor/autoload.php';

use GL\Buffer\FloatBuffer;
use GL\Math\GLM;
use GL\Math\Mat4;
use GL\Math\Vec3;
use Medilies\TryingPhpGlfw\Context;
use Medilies\TryingPhpGlfw\ShaderProgram;
use Medilies\TryingPhpGlfw\TextureLoader;
use Medilies\TryingPhpGlfw\Vertexes\UvCube;

$context = Context::make();
$context->init();

$shaderProgram = new ShaderProgram('instancing', 'instancing');
$context->registerShaderProgram('instancing', $shaderProgram);

$context->registerVertex('uv_cube', new UvCube);

// ! --------------

// additionally create the elements buffer
$matrices = new FloatBuffer;
$c3size = 50;
$matrices->reserve($c3size * $c3size * $c3size * 16);
for ($y = 0; $y < $c3size; $y++) {
    for ($x = 0; $x < $c3size; $x++) {
        for ($z = 0; $z < $c3size; $z++) {
            $m = new Mat4;
            $p = new Vec3($x, $y, $z);
            $m->translate(($p - ($c3size / 2)) * 2.5);
            $matrices->pushMat4($m);
        }
    }
}

glGenBuffers(1, $EBO);
glBindBuffer(GL_ARRAY_BUFFER, $EBO);
glBufferData(GL_ARRAY_BUFFER, $matrices, GL_STATIC_DRAW);

// define additional vertex attributes for the instancing
$context->bindVertexArray('uv_cube');

glEnableVertexAttribArray(2);
glVertexAttribPointer(2, 4, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 16, 0);

glEnableVertexAttribArray(3);
glVertexAttribPointer(3, 4, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 16, (GL_SIZEOF_FLOAT * 4) * 1);

glEnableVertexAttribArray(4);
glVertexAttribPointer(4, 4, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 16, (GL_SIZEOF_FLOAT * 4) * 2);

glEnableVertexAttribArray(5);
glVertexAttribPointer(5, 4, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 16, (GL_SIZEOF_FLOAT * 4) * 3);

glVertexAttribDivisor(2, 1);
glVertexAttribDivisor(3, 1);
glVertexAttribDivisor(4, 1);
glVertexAttribDivisor(5, 1);

$context->unbindVertexArray();

// ! --------------

$texture = TextureLoader::load(__DIR__.'/../src/assets/images/php-logo.png');

// set the shader uniform to point
// the texture unit to our texture
$context->useShaderProgram('instancing');
glUniform1i(
    glGetUniformLocation($context->getShaderProgramRef('instancing'), 'logo'),
    0
);

// update the viewport
glViewport(0, 0, 800, 600);

// enable depth testing, because we are rendering a 3d object with overlapping
// triangles
glEnable(GL_DEPTH_TEST);

$context->loop(function (Context $context) use ($c3size, $matrices) {
    glClearColor(0, 0, 0, 1);
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    //
    $context->useShaderProgram('instancing');

    // time
    $t = glfwGetTime() * 0.1;
    $dist = ($c3size * 2.5) + (cos(($t + 2) * 0.5) * 250);

    //
    $view = new Mat4;
    // we rotate the camera around the y axis and always look at the origin
    // also we zoom in and out for nice effect
    $view->lookAt(
        new Vec3(sin($t) * $dist, 0.0, cos($t) * $dist),
        new Vec3(0.0, 0.0, 1.0),
        new Vec3(0.0, 1.0, 0.0)
    );

    //
    $projection = new Mat4;
    $projection->perspective(GLM::radians(45.0), 800 / 600, 1.0, 10000.0);

    //
    glUniformMatrix4f(
        glGetUniformLocation($context->getShaderProgramRef('instancing'), 'view'),
        GL_FALSE,
        $view
    );
    glUniformMatrix4f(
        glGetUniformLocation($context->getShaderProgramRef('instancing'), 'projection'),
        GL_FALSE,
        $projection
    );

    // bind & draw the vertex array
    $context->bindVertexArray('uv_cube');
    glDrawArraysInstanced(GL_TRIANGLES, 0, 36, $matrices->size() / 16); // TODO: move to Vertex
    $context->unbindVertexArray();
    glDrawArrays(GL_TRIANGLES, 0, 36); // ? applied on instanced
});
