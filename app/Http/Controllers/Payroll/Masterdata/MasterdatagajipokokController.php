<?php

namespace App\Http\Controllers\Payroll\Masterdata;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\Mastergajipokokrequest;
use App\Model\Kepegawaian\Jabatan;
use App\Model\Payroll\Mastergajipokok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterdatagajipokokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gajipokok = Mastergajipokok::with([
            'Jabatan'])->get();

        $jabatan = Jabatan::where('nama_jabatan', '!=', 'Owner')->get();
        
        
        return view('pages.payroll.masterdata.gajipokok',compact('gajipokok','jabatan'));
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
    public function store(Mastergajipokokrequest $request)
    {

        $gajipokok = new Mastergajipokok;
        $gajipokok->id_bengkel = $request['id_bengkel'] = Auth::user()->id_bengkel;
        $gajipokok->id_jabatan = $request->id_jabatan;
        $gajipokok->besaran_gaji = $request->besaran_gaji;
       
        $gajipokok->save(); 
        return redirect()->back()->with('messageberhasil','Data Gaji Pokok Berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id_gaji_pokok)
    {

        $gajipokok = Mastergajipokok::findOrFail($id_gaji_pokok);
        $gajipokok->besaran_gaji = $request->besaran_gaji;

        $gajipokok->update();
        return redirect()->back()->with('messageberhasil','Data Gaji Pokok Berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_gaji_pokok)
    {
        $gajipokok = Mastergajipokok::findOrFail($id_gaji_pokok);
        $gajipokok->delete();

        return redirect()->back()->with('messagehapus','Data Gaji Pokok Berhasil dihapus');
    }

    public function tambahgajipokok(Request $request)
    {

        $gajipokok = new Mastergajipokok;
        $gajipokok->id_bengkel = $request['id_bengkel'] = Auth::user()->id_bengkel;
        $gajipokok->id_jabatan = $request->id_jabatan;
        $gajipokok->besaran_gaji = $request->besaran_gaji;
       
        $gajipokok->save(); 
        return redirect()->back()->with('messageberhasil','Data Gaji Pokok Berhasil ditambahkan');
    }
}
