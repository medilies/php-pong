
// ---

#version 330 core

in vec2 v_uv;

out vec4 fragment_color;

uniform sampler2D logo;

void main() {
    fragment_color = vec4(texture(logo, v_uv).rgb, 1.0);
}
