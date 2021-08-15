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
        switch (strtolower($status)) {
            case 'open':
                return 1;
            case 'considering':
                return 2;
            case 'in progress':
                return 3;
            case 'implemented':
                return 4;
            case 'closed':
                return 5;
            default:
                throw new InvalidArgumentException('The given status is not exists');
                break;
        }
        // return match(strtolower($status)) {
        //     'open' => 1,
        //     'considering' => 2,
        //     'in progress' => 3,
        //     'implemented' => 4,
        //     'closed' => 5,
        // default=> throw new InvalidArgumentException('This status is not exists'),
        // };
    }
}
