<?php

class System
{
    /** windows or linux */
    public static function is_win(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
