<?php

namespace Medilies\PhpPong\OpenGl;

use Exception;

class ShaderProgram
{
    private readonly int $program;

    public function __construct(string $vertexName, string $fragmentName)
    {
        $this->program = glCreateProgram();

        // create, upload and compile the shaders
        $vertexShader = $this->compileVertex($vertexName);
        $fragShader = $this->compileFragment($fragmentName);

        $this->linkProgram($vertexShader, $fragShader);

        // free the shaders
        glDeleteShader($vertexShader);
        glDeleteShader($fragShader);
    }

    private function compileVertex(string $vertexName): int
    {
        return $this->compileShader(
            GL_VERTEX_SHADER,
            $this->getShaderFileContent($vertexName)[0]
        );
    }

    private function compileFragment(string $fragmentName): int
    {
        return $this->compileShader(
            GL_FRAGMENT_SHADER,
            $this->getShaderFileContent($fragmentName)[1]
        );
    }

    /**
     * @return string[]
     */
    private function getShaderFileContent(string $fileName): array
    {
        $path = PROJECT_PATH."/src/resources/shaders/{$fileName}.glsl";
        $shaderCode = file_get_contents($path);

        if (false === $shaderCode) {
            throw new Exception("Couldn't read shader: '{$path}'");
        }

        return explode('// ---', $shaderCode);
    }

    private function compileShader(int $type, string $shaderCode): int
    {
        $vertexShader = glCreateShader($type);

        glShaderSource($vertexShader, $shaderCode);

        glCompileShader($vertexShader);
        glGetShaderiv($vertexShader, GL_COMPILE_STATUS, $success);

        if (! $success) {
            throw new Exception(($type === GL_VERTEX_SHADER ? 'Vertex' : 'Fragment').' shader could not be compiled.');
        }

        return $vertexShader;
    }

    /**
     * create a shader program and link our vertex and fragment shader together
     */
    private function linkProgram(int $vertexShader, int $fragShader): void
    {
        glAttachShader($this->program, $vertexShader);
        glAttachShader($this->program, $fragShader);

        glLinkProgram($this->program);
        glGetProgramiv($this->program, GL_LINK_STATUS, $linkSuccess);
        if (! $linkSuccess) {
            throw new Exception('Shader program could not be linked.');
        }

        // ! not confident of the following code to access logs
        glValidateProgram($this->program);
        glGetProgramiv($this->program, GL_VALIDATE_STATUS, $validationSuccess);
        if (GL_TRUE !== $validationSuccess) {
            glGetShaderiv($this->program, GL_INFO_LOG_LENGTH, $length);

            echo glGetShaderInfoLog($this->program, $length).PHP_EOL;
            throw new Exception('Shader program is not valid.');
        }
    }

    /**
     * Activate this shader program for the coming draw calls.
     */
    public function use(): void
    {
        glUseProgram($this->program);
    }

    public function getRef(): int
    {
        return $this->program;
    }

    public function delete(): void
    {
        glDeleteProgram($this->program);
    }
}
