<?php

namespace Webkul\Depense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Attribute\Traits\CustomAttribute;
use Webkul\Depense\Contracts\Depense as DepenseContract; // Ajoutez cette ligne
use Webkul\User\Models\UserProxy;

class Depense extends Model implements DepenseContract // Implémentez l'interface
{
    use CustomAttribute, SoftDeletes;

    protected $table = 'depenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'category',
        'description',
        'montant',
        'note',
        'mode_paiement',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'montant' => 'float',
    ];

    /**
     * Get the user that owns the depense.
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }
}