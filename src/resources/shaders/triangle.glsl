#version 330 core

layout(location = 0) in vec4 position;
layout(location = 1) in vec4 color;

out vec4 pcolor;

void main() {
    pcolor = color;
    gl_Position = position;
}

// ---

#version 330 core

in vec4 pcolor;

out vec4 fragment_color;

void main() {
    fragment_color = pcolor;
}
