<?php
// app/Models/Dispute.php
namespace App\Models;
use App\Models\BillItem;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Model as Models;

class Dispute extends Models
{
    protected $primaryKey = 'dispute_id';
    public $timestamps    = false;

    protected $fillable = [
        'billing_item_id', 'patient_id', 'datetime',
        'reason', 'status', 'approved_by'
    ];

   public function billItem()
{
    return $this->belongsTo(BillItem::class, 'billing_item_id', 'billing_item_id');
}
    public function patient()  { return $this->belongsTo(Patient::class,  'patient_id'); }
}
