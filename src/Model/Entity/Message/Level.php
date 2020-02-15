<?php

namespace App\Model\Entity\Message;

/**
 * PHP replacement for enumeration. Listing all available message levels.
 *
 * @package App\Model\Entity\Message
 */
class Level
{
    /**
     * Info level
     */
    const INFO = 0;

    /**
     * Warning level
     */
    const WARNING = 1;

    /**
     * Error level
     */
    const ERROR = 2;
}