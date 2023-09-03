<?php

namespace app\components;

/** constant class */

class Mode
{
    const CREATE = 1;
    const READ = 2;
    const UPDATE = 3;
    const DELETE = 4;
    

    public static function getText($mode=2)
    {
        $list = [
            static::CREATE => 'Create',
            static::READ => 'View',
            static::UPDATE => 'Edit',
            static::DELETE => 'Delete',
        ];

        return $list[$mode];
    }

}