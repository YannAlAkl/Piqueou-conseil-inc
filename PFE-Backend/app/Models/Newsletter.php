<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [
        'title',
        'category',
        'content',
        'image',
        'source_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function nomCategorie(): string
    {
        if ($this->category === 'cmmc') {
            return 'CMMC';
        }

        if ($this->category === 'loi25') {
            return 'Loi 25';
        }

        return 'ISO 27001';
    }
}
