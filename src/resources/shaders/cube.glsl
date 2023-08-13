#version 330 core

layout(location = 0) in vec4 a_position;
layout(location = 1) in vec2 a_uv;

out vec2 v_uv;

uniform mat4 model;
uniform mat4 view;
uniform mat4 projection;

void main() {
    v_uv = a_uv;
    gl_Position = projection * view * model * a_position;
}

// ---

#version 330 core

in vec2 v_uv;

out vec4 fragment_color;

void main() {
    fragment_color = vec4(v_uv.x, v_uv.y, 1.0, 1.0);
}
