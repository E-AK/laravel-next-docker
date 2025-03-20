<?php

namespace App\Enums;

enum TaskStatus: string
{
    case TODO = 'todo';
    case DOES = 'does';
    case DONE = 'done';

    public static function fromName(string $name): string
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status->value;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }
}
