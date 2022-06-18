<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Models\ProductSale;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportCashRegisterController extends Controller
{
    
    public function index()
    {
    return view("report_cash_register");
    }

    public function getData(Request $request){
    $totalTj=0;
    $totalMetalic=0;

    $date=Carbon::createFromFormat('d/m/Y',$request->date,config('app.timezone_for_pilates'))->format('Y-m-d');

    $sales=Sale::where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),$date)->get(['id','type_payment','cant_tj','cant_cash']);
    foreach ($sales as $key => $sale) {
        if($sale->type_payment=="card" || $sale->type_payment=="mix"){
            $totalTj+=floatval($sale->cant_tj);
        }
        if($sale->type_payment=="cash" || $sale->type_payment=="mix"){
            $totalMetalic+=floatval($sale->cant_cash);
        }
    }




    return response()->json([
        'earnings_tj_formated'=>Pilates::getFormatMoney($totalTj),
        'earnings_metalic_formated'=>Pilates::getFormatMoney($totalMetalic),
        'earnings_tj'=>$totalTj,
        'earnings_metalic'=>$totalMetalic,
        'total_earnings_formated'=>Pilates::getFormatMoney($totalMetalic+$totalTj),
        'total_earnings'=>$totalMetalic+$totalTj
        ]);
    }

public function printCashRegister(Request $request){
$initial_balance=Pilates::getFormatMoneyForDomPDF($request->initial_balance);
$receipts_expenses=Pilates::getFormatMoneyForDomPDF($request->receipts_expenses);
$earnings_tj=Pilates::getFormatMoneyForDomPDF($request->earnings_tj);
$earnings_metalic=Pilates::getFormatMoneyForDomPDF($request->earnings_metalic);
$earnings_total=Pilates::getFormatMoneyForDomPDF($request->earnings_tj+$request->earnings_metalic);
$date=$request->date;
$status=$request->status;
$counts=$request->input('counts');
$nameFile='arqueo';

$fields_count = [
['text'=>'Billetes de 500 &#8364','id'=>'0','value_start'=>0],
['text'=>'Billetes de 200 &#8364','id'=>'1','value_start'=>0],
['text'=>'Billetes de 100 &#8364','id'=>'2','value_start'=>0],
['text'=>'Billetes de 50 &#8364','id'=>'3','value_start'=>0],
['text'=>'Billetes de 20 &#8364','id'=>'4','value_start'=>0],
['text'=>'Billetes de 10 &#8364','id'=>'5','value_start'=>0],
['text'=>'Billetes de 5 &#8364','id'=>'6','value_start'=>0],
['text'=>'Monedas de 2 &#8364','id'=>'7','value_start'=>0],
['text'=>'Monedas de 1 &#8364','id'=>'8','value_start'=>0],
['text'=>'Monedas de 50 ¢','id'=>'9','value_start'=>0],
['text'=>'Monedas de 20 ¢','id'=>'10','value_start'=>0],
['text'=>'Monedas de 10 ¢','id'=>'11','value_start'=>0],
['text'=>'Monedas de 5 ¢','id'=>'12','value_start'=>0],
['text'=>'Monedas de 2 ¢','id'=>'13','value_start'=>0],
['text'=>'Monedas de 1 ¢','id'=>'14','value_start'=>0],
];

foreach ($counts as $key => $count) {
$fields_count[$key]['value_start']=$count;
}

  /*auditoria: start*/Pilates::setAudit("Arqueo día: $date"); /*auditoria: end*/

$pdf = PDF::loadView('report_cash_register_print', compact('earnings_total','fields_count','counts','initial_balance', 'receipts_expenses', 'earnings_tj', 'earnings_metalic', 'date', 'status'))->setPaper('letter', 'landscape');
//return view('report_cash_register_print', compact('fields_count','counts','initial_balance', 'receipts_expenses', 'earnings_tj', 'earnings_metalic', 'date', 'status'));
return $pdf->stream("$nameFile.pdf");
}

   
}
