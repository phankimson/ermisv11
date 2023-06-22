<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Model\Casts\Decimal;
use App\Http\Model\AccAccountedFast;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccCaseCode;
use App\Http\Model\AccCostCode;
use App\Http\Model\AccStatisticalCode;
use App\Http\Model\AccWorkCode;
use App\Http\Model\AccDepartment;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccObject;
use App\Http\Model\Casts\Date;
use App\Http\Traits\ScopesTraits;
use App\Http\Traits\BootedTraits;
use DB;

class AccDetail extends Model
{
  use ScopesTraits,BootedTraits;
      protected $connection = 'mysql2';

      protected $table = 'acc_detail';

      public $incrementing = false; // and it doesn't even have to be auto-incrementing!

      protected $guarded = []; //Thiếu dòng create bị lỗi Add [code] to fillable property to allow mass assignment on

      protected $with = ['accounted_fast','debit','credit','case_code','statistical_code','cost_code','work_code','department','bank_account'];
      
      protected $keyType = 'string';

      protected static function booted()
      {
          static::BootedBaseTrait();
      }

      protected $casts = [
          'quantity' => Decimal::class,
          'quantity_receipt' => Decimal::class,
          'price'=> Decimal::class,
          'purchase_price'=> Decimal::class,
          'amount'=> Decimal::class,
          'rate'=> Decimal::class,
          'amount_rate'=> Decimal::class,
          'purchase_amount'=> Decimal::class,
          'expiry_date' => Date::class,
          'active' => 'boolean',
      ];

      static public function get_detail($general_id) {
        $result = AccDetail::where('general_id',$general_id)->get();
        return $result;
      }

      static public function get_detail_active($general_id,$active) {
        $result = AccDetail::where('general_id',$general_id)->where('active', $active)->get();
        return $result;
      }

      static public function get_detail_whereNotIn_delete($general_id,$arr) {
        AccDetail::where('general_id',$general_id)->whereNotIn('id',$arr)->delete();
      }

      public function accounted_fast() {
        return $this->belongsTo(AccAccountedFast::class,'accounted_fast','id');
      }

      public function debit() {
        return $this->belongsTo(AccAccountSystems::class,'debit','id');
      }
      public function credit() {
        return $this->belongsTo(AccAccountSystems::class,'credit','id');
      }
      public function case_code() {
        return $this->belongsTo(AccCaseCode::class,'case_code','id');
      }
      public function statistical_code() {
        return $this->belongsTo(AccStatisticalCode::class,'statistical_code','id');
      }
      public function cost_code() {
        return $this->belongsTo(AccCostCode::class,'cost_code','id');
      }
      public function work_code() {
        return $this->belongsTo(AccWorkCode::class,'work_code','id');
      }
      public function department() {
        return $this->belongsTo(AccDepartment::class,'department','id');
      }
      public function bank_account() {
        return $this->belongsTo(AccBankAccount::class,'bank_account','id');
      }
      public function subject_credit() {
        return $this->belongsTo(AccObject::class,'subject_id_credit','id');
      }
}
