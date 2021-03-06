<?php

namespace App\Domains\Payments;

use App\Domains\Invoices\Invoice;
use App\Domains\Users\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = ['price','paid_at','status','user_id','invoice_id'];

    protected $dates = ['paid_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getStatusStringAttribute()
    {
        switch ($this->status){
            case 0:
                return 'Pendenting';
                break;
            case 1:
                return 'Approved';
                break;
            case 2:
                return 'Not Approved';
                break;
            default:
                return 'Not Avaliable';
                break;
        }
    }


}
