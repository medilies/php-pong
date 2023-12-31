#version 330 core

layout(location = 0) in vec2 position;
layout(location = 1) in vec3 color;

out vec4 pcolor;

uniform mat4 u_model;
uniform mat4 u_view;
uniform mat4 u_projection;

void main() {
    pcolor = vec4(color, 1);
    gl_Position = u_projection * u_view * u_model * vec4(position, 0, 1);
}

// ---

#version 330 core

in vec4 pcolor;

out vec4 fragment_color;

void main() {
    fragment_color = pcolor;
}
