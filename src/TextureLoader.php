<?php

namespace Medilies\PhpPong;

use GL\Texture\Texture2D;

class TextureLoader
{
    public static function load(string $path): bool
    {
        // generate a texture, load it from a file and bind it
        glGenTextures(1, $texture);
        glActiveTexture(GL_TEXTURE0);
        // all upcoming GL_TEXTURE_2D operations now have effect on this texture object
        glBindTexture(GL_TEXTURE_2D, $texture);

        // set the texture wrapping parameters
        // here we basically tell opengl to repeat the texture, so when sampling out of bounds
        // it will still give you a result
        glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_WRAP_S, GL_REPEAT);
        glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_WRAP_T, GL_REPEAT);

        // set texture filtering parameters
        glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_MIN_FILTER, GL_LINEAR);
        glTexParameteri(GL_TEXTURE_2D, GL_TEXTURE_MAG_FILTER, GL_LINEAR);

        // PHP-GLFW comes with an image loader based on stb_image
        // with it you can easily create a pixel buffer object to upload to opengl
        $textureData = Texture2D::fromDisk($path);

        glTexImage2D(
            GL_TEXTURE_2D,
            0,
            $textureData->channels() === 3 ? GL_RGB : GL_RGBA, // ! not extensive
            $textureData->width(),
            $textureData->height(),
            0,
            GL_RGB,
            GL_UNSIGNED_BYTE,
            $textureData->buffer()
        );

        // this call generates the mipmap for the texture
        glGenerateMipmap(GL_TEXTURE_2D);

        return $texture;
    }

    // instance+register
    // bind()
    // unbind()
}
