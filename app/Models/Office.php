<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Office extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'cnpj',
        'razao_social',
        'certificate_path',
        'certificate_data',
        'certificate_password',
        'balance',
        'subscription_cnpjs',
    ];

    protected $hidden = [
        'certificate_password',
    ];

    protected function casts(): array
    {
        return [
            'certificate_password' => 'encrypted',
            'balance' => 'integer',
            'subscription_cnpjs' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->hasOne(User::class, 'office_id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'office_id');
    }
}
