<?php

namespace Medilies\PhpPong;

use Medilies\PhpPong\Game\Nodes\Node;
use Medilies\PhpPong\OpenGl\Context;

final class Game
{
    private static $instance;

    /** @var array<string, Node> */
    private array $nodes = [];

    /** @var array<string, array<string, true>> */
    private array $collisions = [];

    private bool $isStarted = false;

    public static function make(Context $context, int $windowWidth, int $windowHeigh): self
    {
        if (! isset(self::$instance)) {
            self::$instance = new self($context, $windowWidth, $windowHeigh);
        }

        return self::$instance;
    }

    private function __construct(
        public Context $context,
        private int $windowWidth,
        private int $windowHeigh,
    ) {
    }

    public function init(): void
    {
        $this->context->createWindow(1080, 720, 'PONG');
        $this->context->init();
    }

    public static function sceneWidth(): int
    {
        return self::$instance->windowWidth;
    }

    public static function sceneHeight(): int
    {
        return self::$instance->windowHeigh;
    }

    // !

    public function loop(): void
    {
        while (! $this->context->getCurrentWindow()->shouldClose()) {
            $this->handleStart();

            glClear(GL_COLOR_BUFFER_BIT);
            glClearColor(0.8, 0.6, 0, 1);

            if ($this->context->isPressed(GLFW_KEY_ESCAPE)) {
                $this->context->closeCurrentWindow();
            }

            if ($this->isStarted) {
                $this->collisions = [];

                foreach ($this->nodes as $node) {
                    $node->move();
                }

                $this->checkCollisions();

                foreach ($this->nodes as $node) {
                    $node->postMove();
                }
            }

            foreach ($this->nodes as $node) {
                $node->draw();
            }

            $this->context->getCurrentWindow()->swapBuffers();
            glfwPollEvents();
        }
    }

    private function checkCollisions(): void
    {
        $checked = [];
        foreach ($this->nodes as $name1 => $node1) {
            foreach ($this->nodes as $name2 => $node2) {
                if (isset($checked[$name2]) || $name1 === $name2) {
                    continue;
                }

                if ($node1->collided($node2)) {
                    $this->collisions[$name1][$name2] = true;
                    $this->collisions[$name2][$name1] = true;
                }
            }
            $checked[$name1] = true;
        }
    }

    private function handleStart(): void
    {
        if ($this->isStarted) {
            return;
        }

        if (! $this->context->getCurrentWindow()->isPressed(GLFW_KEY_SPACE)) {
            return;
        }

        $this->isStarted = true;

        foreach ($this->nodes as $node) {
            $node->reset();
        }

        foreach ($this->nodes as $node) {
            $node->start();
        }
    }

    public static function lost(): void
    {
        self::$instance->isStarted = false;
    }

    // ===============================================
    // Nodes
    // ===============================================

    public function registerNode(Node $node): void
    {
        // TODO: must not be '' or duplicate
        $this->nodes[$node->getName()] = $node;
    }

    public function unregisterNode(string $name): void
    {
        unset($this->nodes[$name]);
    }

    public function getNode(string $name): Node
    {
        return $this->nodes[$name];
    }

    /**
     * @return array<string, true>
     */
    public static function getCollisions(string $name): array
    {
        return self::$instance->collisions[$name] ?? [];
    }
}
