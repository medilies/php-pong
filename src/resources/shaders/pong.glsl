#version 330 core

layout(location = 0) in vec2 position;
layout(location = 1) in vec3 color;

out vec4 pcolor;

void main() {
    pcolor = vec4(color, 1);
    gl_Position = vec4(position, 0, 1);
}

// ---

#version 330 core

in vec4 pcolor;

out vec4 fragment_color;

void main() {
    fragment_color = pcolor;
}
