<?php
/**
 * This example will open a window and draw a 3D cube in it.
 * We utilize the example helpers here to focus on what matter in this specific example.
 */

use GL\Math\{GLM, Vec3, Vec4, Mat4};
use GL\Buffer\FloatBuffer;
use Medilies\TryingPhpGlfw\Context;

require __DIR__.'/vendor/autoload.php';

$context = Context::make();
$context->init();

// compile a simple shader to project the cube
// and output the uv colors to the fragment shader
$cubeShader = ExampleHelper::compileShader(
    file_get_contents(__DIR__.'/src/assets/shaders/vertex/cube.glsl'),
    file_get_contents(__DIR__.'/src/assets/shaders/fragment/cube.glsl')
);

// create a floating point buffer with the vertex and uv data
// of a cube with a 1x1x1 size.
$verticies = new FloatBuffer([
	-0.5, -0.5, -0.5,  0.0, 0.0,
     0.5, -0.5, -0.5,  1.0, 0.0,
     0.5,  0.5, -0.5,  1.0, 1.0,
     0.5,  0.5, -0.5,  1.0, 1.0,
    -0.5,  0.5, -0.5,  0.0, 1.0,
    -0.5, -0.5, -0.5,  0.0, 0.0,

    -0.5, -0.5,  0.5,  0.0, 0.0,
     0.5, -0.5,  0.5,  1.0, 0.0,
     0.5,  0.5,  0.5,  1.0, 1.0,
     0.5,  0.5,  0.5,  1.0, 1.0,
    -0.5,  0.5,  0.5,  0.0, 1.0,
    -0.5, -0.5,  0.5,  0.0, 0.0,

    -0.5,  0.5,  0.5,  1.0, 0.0,
    -0.5,  0.5, -0.5,  1.0, 1.0,
    -0.5, -0.5, -0.5,  0.0, 1.0,
    -0.5, -0.5, -0.5,  0.0, 1.0,
    -0.5, -0.5,  0.5,  0.0, 0.0,
    -0.5,  0.5,  0.5,  1.0, 0.0,

     0.5,  0.5,  0.5,  1.0, 0.0,
     0.5,  0.5, -0.5,  1.0, 1.0,
     0.5, -0.5, -0.5,  0.0, 1.0,
     0.5, -0.5, -0.5,  0.0, 1.0,
     0.5, -0.5,  0.5,  0.0, 0.0,
     0.5,  0.5,  0.5,  1.0, 0.0,

    -0.5, -0.5, -0.5,  0.0, 1.0,
     0.5, -0.5, -0.5,  1.0, 1.0,
     0.5, -0.5,  0.5,  1.0, 0.0,
     0.5, -0.5,  0.5,  1.0, 0.0,
    -0.5, -0.5,  0.5,  0.0, 0.0,
    -0.5, -0.5, -0.5,  0.0, 1.0,

    -0.5,  0.5, -0.5,  0.0, 1.0,
     0.5,  0.5, -0.5,  1.0, 1.0,
     0.5,  0.5,  0.5,  1.0, 0.0,
     0.5,  0.5,  0.5,  1.0, 0.0,
    -0.5,  0.5,  0.5,  0.0, 0.0,
    -0.5,  0.5, -0.5,  0.0, 1.0
]);

// create a vertex array object and upload the vertices
glGenVertexArrays(1, $VAO);
glGenBuffers(1, $VBO);

glBindVertexArray($VAO);
glBindBuffer(GL_ARRAY_BUFFER, $VBO);
glBufferData(GL_ARRAY_BUFFER, $verticies, GL_STATIC_DRAW);

// declare the vertex attributes
// positions
glVertexAttribPointer(0, 3, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 5, 0);
glEnableVertexAttribArray(0);

// uv
glVertexAttribPointer(1, 2, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 5, GL_SIZEOF_FLOAT * 3);
glEnableVertexAttribArray(1);

// unbind
glBindBuffer(GL_ARRAY_BUFFER, 0);
glBindVertexArray(0);

// update the viewport
glViewport(0, 0, 800, 600);

// enable depth testing, because we are rendering a 3d object with overlapping
// triangles
glEnable(GL_DEPTH_TEST);

// Main Loop
// ----------------------------------------------------------------------------
while (!glfwWindowShouldClose($context->getCurrentWindow()->getRef()))
{
    glClearColor(0, 0, 0, 1);
    // note how we are clearing both the DEPTH and COLOR buffers.
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT);

    // use the shader, will active the given shader program
    // for the coming draw calls.
    glUseProgram($cubeShader);

    // define the model matrix aka the cubes postion in the world
    $model = new Mat4;
    // because we want the cube to spin, we rotate the matrix based
    // on the elapsed time.
    $model->rotate(glfwGetTime() * 2, new Vec3(0.0, 1.0, 0.0));
    $model->rotate(glfwGetTime() * 2, new Vec3(0.0, 0.0, 1.0));

    // next the view matrix, this is the camera / eye position and rotation
	$view = new Mat4;
    // you can imagine the camera is beeing moved back by 2 units here.
    $view->translate(new Vec3(0.0, 0.0, -2));

    // and finally the projection matrix, this is the perspective matrix.
	$projection = new Mat4;
	$projection->perspective(GLM::radians(70.0), 800 / 600, 0.1, 100.0);

    // now set the uniform variables in the shader.
    // note that we use `glUniformMatrix4f` instead of `glUniformMatrix4fv` to pass a single matrix.
    glUniformMatrix4f(glGetUniformLocation($cubeShader, "model"), GL_FALSE, $model);
    glUniformMatrix4f(glGetUniformLocation($cubeShader, "view"), GL_FALSE, $view);
    glUniformMatrix4f(glGetUniformLocation($cubeShader, "projection"), GL_FALSE, $projection);

    // bind & draw the vertex array
    glBindVertexArray($VAO);
    glDrawArrays(GL_TRIANGLES, 0, 36);

    // swap the windows framebuffer and
    // poll queued window events.
    glfwSwapBuffers($context->getCurrentWindow()->getRef());
    glfwPollEvents();
}


// stop & cleanup
glDeleteVertexArrays(1, $VAO);
glDeleteBuffers(1, $VBO);

// !

use GL\Geometry\ObjFileParser;
use GL\Texture\Texture2D;

/**
 * To reduce the amount of boilerplate for each example this file contains
 * a collection of helpers / common code that is used by the examples.
 */
class ExampleHelper
{
    /**
     * Compiles a basic shader program.
     */
    public static function compileShader(string $vertexShaderSource, string $fragmentShaderSource) : int
    {
        $vertexShader = glCreateShader(GL_VERTEX_SHADER);
        glShaderSource($vertexShader, $vertexShaderSource);
        glCompileShader($vertexShader);
        glGetShaderiv($vertexShader, GL_COMPILE_STATUS, $success);
        if (!$success) {
            throw new Exception('Vertex shader compilation failed: ' . glGetShaderInfoLog($vertexShader, 4096));
        }

        // create, upload and compile the fragment shader
        $fragShader = glCreateShader(GL_FRAGMENT_SHADER);
        glShaderSource($fragShader, $fragmentShaderSource);
        glCompileShader($fragShader);
        glGetShaderiv($fragShader, GL_COMPILE_STATUS, $success);
        if (!$success) {
            throw new Exception("Fragment shader could not be compiled: " . glGetShaderInfoLog($fragShader, 4096));
        }

        // create a shader programm and link our vertex and framgent shader together
        $shaderProgram = glCreateProgram();
        glAttachShader($shaderProgram, $vertexShader);
        glAttachShader($shaderProgram, $fragShader);
        glLinkProgram($shaderProgram);

        glGetProgramiv($shaderProgram, GL_LINK_STATUS, $linkSuccess);
        if (!$linkSuccess) {
            throw new Exception("Shader program could not be linked.");
        }

        // free the shders
        glDeleteShader($vertexShader);
        glDeleteShader($fragShader);

        return $shaderProgram;
    }

    /**
     * Creates a cube vertex buffer
     * Returns the VBO and VAO
     */
    public static function createCubeVBO()
    {
        $verticies = new FloatBuffer([
            -0.5, -0.5, -0.5,  0.0, 0.0,
             0.5, -0.5, -0.5,  1.0, 0.0,
             0.5,  0.5, -0.5,  1.0, 1.0,
             0.5,  0.5, -0.5,  1.0, 1.0,
            -0.5,  0.5, -0.5,  0.0, 1.0,
            -0.5, -0.5, -0.5,  0.0, 0.0,

            -0.5, -0.5,  0.5,  0.0, 0.0,
             0.5, -0.5,  0.5,  1.0, 0.0,
             0.5,  0.5,  0.5,  1.0, 1.0,
             0.5,  0.5,  0.5,  1.0, 1.0,
            -0.5,  0.5,  0.5,  0.0, 1.0,
            -0.5, -0.5,  0.5,  0.0, 0.0,

            -0.5,  0.5,  0.5,  1.0, 0.0,
            -0.5,  0.5, -0.5,  1.0, 1.0,
            -0.5, -0.5, -0.5,  0.0, 1.0,
            -0.5, -0.5, -0.5,  0.0, 1.0,
            -0.5, -0.5,  0.5,  0.0, 0.0,
            -0.5,  0.5,  0.5,  1.0, 0.0,

             0.5,  0.5,  0.5,  1.0, 0.0,
             0.5,  0.5, -0.5,  1.0, 1.0,
             0.5, -0.5, -0.5,  0.0, 1.0,
             0.5, -0.5, -0.5,  0.0, 1.0,
             0.5, -0.5,  0.5,  0.0, 0.0,
             0.5,  0.5,  0.5,  1.0, 0.0,

            -0.5, -0.5, -0.5,  0.0, 1.0,
             0.5, -0.5, -0.5,  1.0, 1.0,
             0.5, -0.5,  0.5,  1.0, 0.0,
             0.5, -0.5,  0.5,  1.0, 0.0,
            -0.5, -0.5,  0.5,  0.0, 0.0,
            -0.5, -0.5, -0.5,  0.0, 1.0,

            -0.5,  0.5, -0.5,  0.0, 1.0,
             0.5,  0.5, -0.5,  1.0, 1.0,
             0.5,  0.5,  0.5,  1.0, 0.0,
             0.5,  0.5,  0.5,  1.0, 0.0,
            -0.5,  0.5,  0.5,  0.0, 0.0,
            -0.5,  0.5, -0.5,  0.0, 1.0
        ]);

        // create a vertex array object and upload the vertices
        glGenVertexArrays(1, $VAO);
        glGenBuffers(1, $VBO);

        glBindVertexArray($VAO);
        glBindBuffer(GL_ARRAY_BUFFER, $VBO);
        glBufferData(GL_ARRAY_BUFFER, $verticies, GL_STATIC_DRAW);

        // declare the vertex attributes
        // positions
        glVertexAttribPointer(0, 3, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 5, 0);
        glEnableVertexAttribArray(0);

        // uv
        glVertexAttribPointer(1, 2, GL_FLOAT, GL_FALSE, GL_SIZEOF_FLOAT * 5, GL_SIZEOF_FLOAT * 3);
        glEnableVertexAttribArray(1);

        // unbind
        glBindBuffer(GL_ARRAY_BUFFER, 0);
        glBindVertexArray(0);

        return [$VAO, $VBO];
    }

    public static function loadTexture(string $path, int $format = GL_RGB) : int
    {
        // generate a texture, load it from a file and bind it
        glGenTextures(1, $texture);
        glActiveTexture(GL_TEXTURE0);
        glBindTexture(GL_TEXTURE_2D, $texture); // all upcoming GL_TEXTURE_2D operations now have effect on this texture object

        // set the texture wrapping parameters
        // here we basically tell opengl to repeat the texture, so when sampling out of bounds
        // it will still give you a result
        glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_WRAP_S, GL_REPEAT);
        glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_WRAP_T, GL_REPEAT);

        // set texture filtering parameters
        glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_MIN_FILTER, GL_LINEAR);
        glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_MAG_FILTER, GL_LINEAR);

        // PHP-GLFW comes with an image loader based on stb_image
        // with it you can easly create a pixel buffer object to upload to opengl
        $textureData = Texture2D::fromDisk($path);
        if ($textureData->channels() == 3) {
            $format = GL_RGB;
        } else if ($textureData->channels() == 4) {
            $format = GL_RGBA;
        }
        glTexImage2D(GL_TEXTURE_2D, 0, $format, $textureData->width(), $textureData->height(), 0, $format, GL_UNSIGNED_BYTE, $textureData->buffer());

        // this call generates the mipmaps for the texture
        glGenerateMipmap(GL_TEXTURE_2D);

        return $texture;
    }

    public static function getShipObj() : ObjFileParser
    {
        // ensure zip extension is loaded
        if (!extension_loaded('zip')) {
            throw new \Exception('The zip extension is required to run this example');
        }

        if (!file_exists(__DIR__ . '/ship_light.obj')) {
            $zip = new ZipArchive();
            $zip->open(__DIR__ . '/ship_light.obj.zip');
            $zip->extractTo(__DIR__);
            $zip->close();
        }

        // load an object file with the ObjFileParser class, the assest we are loading
        // is downloaded from kenney.nl, he provides a bunch of low poly free to use assets.
        $mesh = new \GL\Geometry\ObjFileParser(__DIR__ . '/ship_light.obj');

        return $mesh;
    }
}
