<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Status extends Model
{
    use HasFactory;

    public static function getId(string $status)
    {
        return match(strtolower($status)) {
            'open' => 1,
            'considering' => 2,
            'in progress' => 3,
            'implemented' => 4,
            'closed' => 5,
        default=> throw new InvalidArgumentException('This status is not exists'),
        };
    }
}
