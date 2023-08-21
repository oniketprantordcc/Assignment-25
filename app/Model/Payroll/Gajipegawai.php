<?php

namespace App\Model\Payroll;

use App\Model\Accounting\Jenistransaksi;
use App\Model\Accounting\Payable\Pajak;
use App\Model\Kepegawaian\Pegawai;
use App\Scopes\OwnershipScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Gajipegawai extends Model
{
    use SoftDeletes;

    protected $table = "tb_payroll_perhitungan_gaji";

    protected $primaryKey = 'id_gaji_pegawai';

    protected $fillable = [
        'id_bengkel',
        'id_jenis_transaksi',
        'bulan_gaji',
        'grand_total_gaji',
        'grand_total_tunjangan',
        'grand_total_pph21',
        'keterangan',
        'status_diterima',
        'status_dana',
        'status_jurnal',
        'status_pajak',
        'status_aktif'
    ];

    protected $hidden =[ 
        'created_at',
        'deleted_at'
    ];

    public $timestamps = true;

    public function Detailpegawai()
    {
        return $this->belongsToMany(Pegawai::class,'tb_payroll_detail_gaji','id_gaji_pegawai','id_pegawai')->withPivot('total_tunjangan','total_gaji','total_pph21','total_pokok');
    }

    public function Detailtunjangan()
    {
        return $this->belongsToMany(Mastertunjangan::class,'tb_payroll_detail_tunjangan','id_gaji_pegawai','id_tunjangan')->withPivot('id_pegawai');
    }

    public function Jenistransaksi(){
        return $this->belongsTo(Jenistransaksi::class,'id_jenis_transaksi','id_jenis_transaksi');
    }

    protected static function booted()
    {
        static::addGlobalScope(new OwnershipScope);
    }
}
