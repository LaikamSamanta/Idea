<?php

namespace App\Models;

use App\IdeaStatus;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaFactory> */
    use HasFactory;

    protected $casts = [
        'links' => AsArrayObject::class, // Pārvērš 'links' lauku par masīvu, kad tas tiek piekļūts, un saglabā to kā JSON datubāzē
        'status' => IdeaStatus::class, // Pārvērš 'status' lauku par IdeaStatus enum, kad tas tiek piekļūts, un saglabā to kā string datubāzē(php artisan make:enum IdeaStatus --unit=string)
    ];
}
