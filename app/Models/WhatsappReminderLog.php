<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappReminderLog extends Model
{
    protected $fillable = [
        'invoice_id',
        'customer_id',
        'phone',
        'reminder_type',
        'message',
        'status',
        'sent_at',
        'response',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reminderTypeLabel(): string
    {
        return match ($this->reminder_type) {
            '3_days_before' => 'H-3 Jatuh Tempo',
            'due_date'      => 'Hari Jatuh Tempo',
            'overdue_1_day' => 'H+1 Lewat Jatuh Tempo',
            'manual'        => 'Manual',
            default         => $this->reminder_type,
        };
    }
}
