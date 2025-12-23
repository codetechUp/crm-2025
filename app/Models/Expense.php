<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'date', 'amount', 'description', 'notes', 'category_id',
        'user_id', 'payment_method_id', 'project_id', 'client_id',
        'vendor_id', 'receipt_path', 'receipt_filename', 'status',
        'recurring', 'recurring_until', 'tax_amount', 'tax_rate',
        'metadata', 'approved_by', 'approved_at', 'paid_by', 'paid_at'
    ];
    
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'recurring_until' => 'date',
        'metadata' => 'array'
    ];
    
    // Relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
    
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
    
    // Scopes pour le journal
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }
    
    public function scopeThisYear($query)
    {
        return $query->whereYear('date', now()->year);
    }
    
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
    
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
    
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'submitted');
    }
    
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
    
    public function scopeWithReceipt($query)
    {
        return $query->whereNotNull('receipt_path');
    }
    
    // Accesseurs
    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->tax_amount;
    }
    
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' €';
    }
    
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d/m/Y');
    }
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'paid' => 'bg-blue-100 text-blue-800',
            'reimbursed' => 'bg-purple-100 text-purple-800'
        ];
        
        return $badges[$this->status] ?? 'bg-gray-100';
    }
}