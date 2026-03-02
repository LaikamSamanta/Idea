<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\IdeaFactory;
use App\IdeaStatus;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Idea extends Model
{
    /** @use HasFactory<IdeaFactory> */
    use HasFactory;

    protected $casts = [
        'links' => AsArrayObject::class, // Pārvērš 'links' lauku par masīvu, kad tas tiek piekļūts, un saglabā to kā JSON datubāzē
        'status' => IdeaStatus::class, // Pārvērš 'status' lauku par IdeaStatus enum, kad tas tiek piekļūts, un saglabā to kā string datubāzē(php artisan make:enum IdeaStatus --unit=string)
    ];

    protected $attributes = [
        'status' => IdeaStatus::PENDING, // Noklusējuma vērtība 'status' laukam ir 'pending' no IdeaStatus enum
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Definē attiecības ar User modeli, norādot, ka katra ideja pieder vienam lietotājam
    }

    public function steps() {
        return $this->hasMany(Step::class); // Definē attiecības ar Step modeli, norādot, ka katrai idejai var būt vairāki soļi
    }
   
}
