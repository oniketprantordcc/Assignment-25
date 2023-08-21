<?php

namespace App\Model\Kepegawaian;

use App\Model\Payroll\Detailtunjangan;
use App\Model\Payroll\Mastergajipokok;
use App\Model\Payroll\MasterPTKP;
use App\Model\Payroll\Mastertunjangan;
use App\Model\SingleSignOn\Cabang;
use App\Scopes\OwnershipScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Pegawai extends Model
{
    use SoftDeletes;

    protected $table = "tb_kepeg_master_pegawai";

    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'id_bengkel',
        'id_ptkp',
        'id_jabatan',
        'nama_pegawai',
        'nama_panggilan',
        'nik_pegawai',
        'npwp_pegawai',
        'kode_pegawai',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'kota_asal',
        'no_telp',
        'agama',
        'pendidikan_terakhir',
        'tanggal_masuk',
        'id_bengkel',
        'id_cabang'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }

    public function PTKP()
    {
        return $this->belongsTo(MasterPTKP::class, 'id_ptkp', 'id_ptkp');
    }

    public function Detailtunjangan()
    {
        return $this->belongsToMany(Mastertunjangan::class,'tb_payroll_detail_tunjangan','id_pegawai','id_tunjangan');
    }

    public static function getId()
    {
        // return $this->orderBy('id_sparepart')->take(1)->get();
        $getId = DB::table('tb_kepeg_master_pegawai')->orderBy('id_pegawai', 'DESC')->take(1)->get();
        if (count($getId) > 0) return $getId;
        return (object)[
            (object)[
                'id_pegawai' => 0
            ]
        ];
    }

    public function absensi_mekanik()
    {
        return $this->hasMany(Absensi::class, 'id_pegawai')->whereDate('tanggal_absensi', Carbon::today())->where('absensi', 'Absen_Pagi');
    }

    protected static function booted()
    {
        static::addGlobalScope(new OwnershipScope);
    }
}
