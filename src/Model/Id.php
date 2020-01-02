<?php

namespace BeeJeeMVC\Model;

class Id
{
    /**
     * @var int
     */
    private $value;

    /**
     * @param int|null $value
     */
    public function __construct(?int $value = null)
    {
        if ($value) {
            $this->value = $value;
        }
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
