#version 330 core
out vec4 fragment_color;

in vec2 v_uv;
uniform sampler2D logo;

void main() {
    fragment_color = vec4(texture(logo, v_uv).rgb, 1.0) * vec4(v_uv, 1.0, 1.0);
}
