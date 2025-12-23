<?php
// app/Models/ExpenseCategory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'name', 'description', 'color', 'order', 'is_active', 'parent_id'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    // Relations
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(ExpenseCategory::class, 'parent_id');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}

