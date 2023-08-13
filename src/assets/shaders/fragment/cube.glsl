#version 330 core
out vec4 fragment_color;

in vec2 v_uv;

void main() {
    fragment_color = vec4(v_uv.x, v_uv.y, 1.0, 1.0);
}
