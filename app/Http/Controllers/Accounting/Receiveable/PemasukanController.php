<?php

namespace App\Http\Controllers\Accounting\Receiveable;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PointOfSales\Laporan\LaporanServiceController;
use App\Http\Controllers\PointOfSales\Pembayaran\PembayaranSparepartController;
use App\Model\Marketplace\Transaksi;
use App\Model\PointOfSales\LaporanPenjualanSparepart;
use App\Model\PointOfSales\LaporanService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pemasukankasir = LaporanPenjualanSparepart::groupBy('tanggal_laporan','status_jurnal')
            ->selectRaw('SUM(total_tagihan) as grand_total, tanggal_laporan, COUNT(id_laporan) as jumlah_transaksi, status_jurnal')
            ->get();
        $transaksionline = Transaksi::where('transaksi_status','=','Diterima')->groupBy('tanggal_transaksi')->selectRaw('SUM(harga_total) as total_harga, tanggal_transaksi, COUNT(id_transaksi_online) as jumlah_transaksi_online')->get();
        
        $laporanservice = LaporanService::groupBy('tanggal_laporan','status_jurnal')->selectRaw('SUM(total_tagihan) as tagihan_total, tanggal_laporan, COUNT(id_laporan_service) as jumlah_service, status_jurnal')->get();
        $today = Carbon::now()->isoFormat('dddd');
        $tanggal = Carbon::now()->format('j F Y');

        // $total_pemasukan_online2 = Transaksi::where('transaksi_status','DIKIRIM' || 'transaksi_status', 'DITERIMA')->sum('harga_total');
        // return $total_pemasukan_online2;

        return view('pages.accounting.receiveable.pemasukan',
        ['total_pemasukan' => LaporanPenjualanSparepart::sum('total_tagihan'), 
        'total_pemasukan_online' => Transaksi::where('transaksi_status', 'DITERIMA')->sum('harga_total'),
        'total_service' => LaporanService::sum('total_tagihan')], compact('today','tanggal','pemasukankasir','transaksionline','laporanservice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($tanggal_laporan)
    {
        $detail = LaporanPenjualanSparepart::with(['penjualan_sparepart'])->where('tanggal_laporan', $tanggal_laporan)->get();
        
        return view('pages.accounting.receiveable.detailpemasukan', ['total_per_tanggal' => LaporanPenjualanSparepart::where('tanggal_laporan', $tanggal_laporan)->sum('total_tagihan')], compact('detail','tanggal_laporan'));
    }

    public function Pemasukanonline($tanggal_transaksi)
    {
        $online = Transaksi::with(['Detailtransaksi'])->where('tanggal_transaksi', $tanggal_transaksi)->get();
        
        return view('pages.accounting.receiveable.detailonline', ['sub_total' => Transaksi::where('tanggal_transaksi', $tanggal_transaksi)->sum('harga_total')], compact('online','tanggal_transaksi'));
    }

    public function Laporanservice($tanggal_laporan)
    {
        $service = LaporanService::with(['penerimaan_service'])->where('tanggal_laporan', $tanggal_laporan)->get();
        
        return view('pages.accounting.receiveable.detailservice', ['sub_total' => LaporanService::where('tanggal_laporan', $tanggal_laporan)->sum('total_tagihan')], compact('service','tanggal_laporan'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
