<?php

namespace Medilies\TryingPhpGlfw\Nodes;

use Medilies\TryingPhpGlfw\Context;

abstract class Node
{
    public function __construct(
        protected Context $context
    )
    {
    }

    abstract public function act();
}
