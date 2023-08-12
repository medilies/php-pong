<?php

namespace Medilies\TryingPhpGlfw;

use Exception;

class ShaderProgram
{
    private mixed $program;

    public function __construct(
        private readonly string $vertexName,
        private readonly string $fragmentName
    ) {
        $vertexShader = $this->compileVertex();
        $fragShader = $this->compileFragment();

        $this->createProgram($vertexShader, $fragShader);

        // free the shaders
        glDeleteShader($vertexShader);
        glDeleteShader($fragShader);
    }

    private function compileVertex(): mixed
    {
        // create, upload and compile the vertex shader
        $vertexShader = glCreateShader(GL_VERTEX_SHADER);

        glShaderSource($vertexShader, file_get_contents(__DIR__."/assets/shaders/vertex/{$this->vertexName}.glsl"));

        glCompileShader($vertexShader);
        glGetShaderiv($vertexShader, GL_COMPILE_STATUS, $success);

        if (! $success) {
            throw new Exception('Vertex shader could not be compiled.');
        }

        return $vertexShader;
    }

    private function compileFragment(): mixed
    {
        // create, upload and compile the fragment shader
        $fragShader = glCreateShader(GL_FRAGMENT_SHADER);

        glShaderSource($fragShader, file_get_contents(__DIR__."/assets/shaders/fragment/{$this->fragmentName}.glsl"));

        glCompileShader($fragShader);
        glGetShaderiv($fragShader, GL_COMPILE_STATUS, $success);

        if (! $success) {
            throw new Exception('Fragment shader could not be compiled.');
        }

        return $fragShader;
    }

    private function createProgram(mixed $vertexShader, mixed $fragShader): void
    {
        // create a shader program and link our vertex and fragment shader together
        $this->program = glCreateProgram();
        glAttachShader($this->program, $vertexShader);
        glAttachShader($this->program, $fragShader);
        glLinkProgram($this->program);

        glGetProgramiv($this->program, GL_LINK_STATUS, $linkSuccess);

        if (! $linkSuccess) {
            throw new Exception('Shader program could not be linked.');
        }
    }

    public function use(): void
    {
        glUseProgram($this->program);
    }

    public function getRef(): int
    {
        return $this->program;
    }

    public function delete()
    {
        glDeleteProgram($this->program);
    }
}
