<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingDocument extends Model
{
    protected $fillable = [
        'client_id', 'organization_id', 'type', 'document_number',
        'amount', 'tax_amount', 'issue_date', 'due_date', 'currency', 'status', 'file_path', 'generated_by',
        'rate', 'amount_in_words'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'billing_document_id');
    }

    public function clientPayments()
    {
        return $this->hasMany(ClientPayment::class, 'billing_document_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (empty($document->document_number)) {
                $prefix = strtoupper(substr($document->type, 0, 3));
                $year = date('Y');
                $lastDoc = self::where('type', $document->type)
                               ->whereYear('created_at', $year)
                               ->orderBy('id', 'desc')
                               ->first();

                if ($lastDoc && preg_match('/-(\d+)$/', $lastDoc->document_number, $matches)) {
                    $nextNum = intval($matches[1]) + 1;
                } else {
                    $nextNum = 1;
                }

                $document->document_number = sprintf("%s-%s-%05d", $prefix, $year, $nextNum);
            }
        });
    }
}
