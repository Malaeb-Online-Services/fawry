<?php

namespace AymanZayedElshehawy\Fawry\Exceptions;

use Exception;

class FawryException extends Exception
{
    protected array $context = [];

    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }
} 