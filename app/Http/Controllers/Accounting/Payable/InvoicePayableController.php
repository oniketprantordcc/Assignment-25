<?php

namespace App\Http\Controllers\Accounting\Payable;

use App\Http\Controllers\Controller;
use App\Model\Accounting\Jenistransaksi;
use App\Model\Accounting\Payable\InvoicePayable;
use App\Model\Accounting\Payable\InvoicePayabledetail;
use App\Model\Inventory\Rcv\Rcv;
use App\Model\Kepegawaian\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicePayableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice = InvoicePayable::with([
            'Rcv.Detail','Rcv'
        ])->get();

      

        $jenis_transaksi = Jenistransaksi::all();
        $today = Carbon::now()->isoFormat('dddd');
        $tanggal = Carbon::now()->format('j F Y');

        return view('pages.accounting.payable.invoice.invoice',['hutang' => InvoicePayable::where('status_prf','Belum Dibuat')->sum('total_pembayaran')], 
        compact('invoice','today','tanggal','jenis_transaksi','rcv'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
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
    public function show($id_payable_invoice)
    {
        $invoice = InvoicePayable::with('Detailinvoice')->findOrFail($id_payable_invoice);

        return view('pages.accounting.payable.invoice.detail')->with([
            'invoice' => $invoice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_payable_invoice)
    {
        $invoice = InvoicePayable::findOrFail($id_payable_invoice);
        $invoice->kode_invoice = $request->kode_invoice;
        $invoice->id_pegawai = $request['id_pegawai'] = Auth::user()->pegawai->id_pegawai;
        $invoice->tanggal_invoice = $request->tanggal_invoice;
        $invoice->tenggat_invoice = $request->tenggat_invoice;
        $invoice->deskripsi_invoice = $request->deskripsi_invoice;
        $invoice->total_pembayaran = $request->total_pembayaran;

        $invoice->status_prf ='Belum diBuat';
        $invoice->status_jurnal ='Belum diPosting';    
        $invoice->save();
        
        $invoice->Detailinvoice()->sync($request->sparepart);
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_payable_invoice)
    {
        $invoice = InvoicePayable::findOrFail($id_payable_invoice);
        InvoicePayabledetail::where('id_payable_invoice', $id_payable_invoice)->delete();
        $invoice->delete();

        return redirect()->back()->with('messagehapus','Data Invoice Berhasil dihapus');
    }

    public function CetakInvoice($id_payable_invoice)
    {
        $invoice = InvoicePayable::with('Detailinvoice','Rcv','PO','Supplier','Pegawai')->findOrFail($id_payable_invoice);
        // return $invoice;
        $now = Carbon::now();
        return view('print.Accounting.cetak-invoice', compact('invoice','now'));
    }
}
