<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'cnpj',
        'razao_social',
        'certificate_path',
        'certificate_data',
        'certificate_password',
        'balance',
        'subscription_cnpjs',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'certificate_password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'certificate_password' => 'encrypted',
            'balance' => 'integer',
            'subscription_cnpjs' => 'integer',
        ];
    }

    /**
     * Get the user associated with the office.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'office_id');
    }

    /**
     * Get the clients associated with the office.
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'office_id');
    }

    /**
     * Get the payments associated with the office.
     */
//    public function payments()
//    {
//        return $this->hasMany(Payment::class, 'office_id');
//    }
}
