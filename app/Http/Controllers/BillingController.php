<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationDeleteInvoice;
use App\Models\Configuration;
use App\Models\Invoice;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Tax;
use App\Models\TaxSale;
use App\Models\Ticket;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PDF;

class BillingController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('administration_billing');
  }

  public function destroy(ValidationDeleteInvoice $request)
  {
    $errors = 0;
    $cantSuccsess = 0;
    $idsProducts = $request['id'];
    foreach ($idsProducts as $key => $id) {

      if (Invoice::where('id', $id)->delete()) {
        $cantSuccsess++;
      } else {
        $errors++;
      }
    }
      /*auditoria: start*/Pilates::setAudit("Baja factura ids: ".implode(', ', $idsProducts)); /*auditoria: end*/
    return $cantSuccsess <= 1 ?
      redirect('administration_billing')->with('success', $cantSuccsess . ' factura eliminada con éxito')
      :
      redirect('administration_billing')->with('success', $cantSuccsess . ' facturas eliminadas con éxito');
  }

  public function dataTable(Request $request)
  {
    $clientInvoice = new Invoice();
    $clientInvoice = $clientInvoice->getClientInvoiceDataTable($request);
    return response()->json($clientInvoice);
  }

  public function downloadInvoice($idSale = false)
  {
    $invoiceSale = Invoice::join('sale', 'invoice.id_sale', '=', 'sale.id')
      ->where('invoice.id_sale', $idSale)->get(['*', 'invoice.id as code_invoice'])->first();
    if (empty($invoiceSale)) {
      return redirect('management_sale');
    }

    $productsSale = ProductSale::where('id_sale', $invoiceSale->id_sale)
      ->get(["*"])
      ->map(function ($product) {
        $product['total_product'] = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalByProductAmount($product));
        $product['price'] = Pilates::getFormatMoneyForDomPDF($product->price);
        $product['discount'] = Pilates::getFormatPercent($product->discount);
        $taxTmp = TaxSale::where('id_product_sale', $product->id)->get();
        $product['tax'] = $taxTmp->map(function ($tax) {
          if ($tax->type == 'percent') {
            $tax->tax = Pilates::getFormatPercent($tax->tax);
          }
          if ($tax->type == 'money') {
            $tax->tax = Pilates::getFormatMoneyForDomPDF($tax->tax);
          }
          return $tax;
        });

        return $product;
      })->values()->all();

    $time = strtotime($invoiceSale->date_create);
    $invoiceSale->date_create = date("d.m.Y", $time);

    $tmpProducts = ProductSale::where('id_sale', $invoiceSale->id_sale)->get();

    $subtotal = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalAmountSimple($tmpProducts));
    $totalDiscount = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalDiscount($tmpProducts));
    $totalTaxes = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalTaxes($tmpProducts));
    $totalAmount = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalAmount($tmpProducts));

    // return view('invoice/simple_invoice1', compact('invoiceSale','productsSale','subtotal','totalDiscount','totalTaxes','totalAmount'));

    $config = Configuration::first();
    $pdf = PDF::loadView('invoice/simple_invoice1',  compact('invoiceSale', 'productsSale', 'subtotal', 'totalDiscount', 'totalTaxes', 'totalAmount', 'config'));
    /*auditoria: start*/Pilates::setAudit("Descarga factura con id: $invoiceSale->code_invoice"); /*auditoria: end*/
    return $pdf->download("Factura_n$invoiceSale->code.pdf");
  }

  public function printInvoice($idSale = false)
  {
    $invoiceSale = Invoice::join('sale', 'invoice.id_sale', '=', 'sale.id')
      ->where('invoice.id_sale', $idSale)->get(['*', 'invoice.id as code_invoice'])->first();

    if (empty($invoiceSale)) {
      return redirect('management_sale');
    }

    $productsSale = ProductSale::where('id_sale', $invoiceSale->id_sale)
      ->get(["*"])
      ->map(function ($product) {
        $product['total_product'] = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalByProductAmount($product));
        $product['price'] = Pilates::getFormatMoneyForDomPDF($product->price);
        $product['discount'] = Pilates::getFormatPercent($product->discount);
        $taxTmp = TaxSale::where('id_product_sale', $product->id)->get();
        $product['tax'] = $taxTmp->map(function ($tax) {
          if ($tax->type == 'percent') {
            $tax->tax = Pilates::getFormatPercent($tax->tax);
          }
          if ($tax->type == 'money') {
            $tax->tax = Pilates::getFormatMoneyForDomPDF($tax->tax);
          }
          return $tax;
        });



        return $product;
      })->values()->all();

    $time = strtotime($invoiceSale->date_create);
    $invoiceSale->date_create = date("d.m.Y", $time);

    $tmpProducts = ProductSale::where('id_sale', $invoiceSale->id_sale)->get();

    $subtotal = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalAmountSimple($tmpProducts));
    $totalDiscount = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalDiscount($tmpProducts));
    $totalTaxes = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalTaxes($tmpProducts));
    $totalAmount = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalAmount($tmpProducts));

    // return view('invoice/simple_invoice1', compact('invoiceSale','productsSale','subtotal','totalDiscount','totalTaxes','totalAmount'));

    $config = Configuration::first();
    $pdf = PDF::loadView('invoice/simple_invoice1',  compact('invoiceSale', 'productsSale', 'subtotal', 'totalDiscount', 'totalTaxes', 'totalAmount', 'config'));
    /*auditoria: start*/Pilates::setAudit("Impresión factura id: $invoiceSale->code_invoice"); /*auditoria: end*/
    return $pdf->stream("Factura_n$invoiceSale->code.pdf");
  }


  public function printTicket($idSale = false)
  {

    $sale = Sale::where('sale.id', $idSale)->get(['*'])->first();
    if (empty($sale)) {
      return redirect('management_sale');
    }

    $productsSale = ProductSale::where('id_sale', $idSale)
      ->get(["*"])
      ->map(function ($product) {
        $product['total_product'] = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalByProductAmount($product));
        $product['price'] = Pilates::getFormatMoneyForDomPDF($product->price);
        $product['discount'] = Pilates::getFormatPercent($product->discount);
        $taxTmp = TaxSale::where('id_product_sale', $product->id)->get();
        $product['tax'] = $taxTmp->map(function ($tax) {
          if ($tax->type == 'percent') {
            $tax->tax = Pilates::getFormatPercent($tax->tax);
          }
          if ($tax->type == 'money') {
            $tax->tax = Pilates::getFormatMoneyForDomPDF($tax->tax);
          }
          return $tax;
        });

        return $product;
      })->values()->all();


    $idEmployee = auth()->user()->id;

    $ticket = Ticket::create([
      'date_create' => now(),
      'id_sale' => $idSale,
      'id_employee' => $idEmployee,
      'type' => 'ordinary'
    ]);

    $tmpProducts = ProductSale::where('id_sale', $idSale)->get();

    $subtotal = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalAmountSimple($tmpProducts));
    $totalDiscount = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalDiscount($tmpProducts));
    $totalTaxes = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalTaxes($tmpProducts));
    $totalAmount = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalAmount($tmpProducts));

    $sale = Sale::find($idSale);
    $sale->increment('ticket_count', 1);
    $config = Configuration::first();
    //return view('ticket/ticket1', compact('productsSale','subtotal','totalDiscount','totalTaxes','totalAmount','config','sale','ticket'));


    $pdf = PDF::loadView('ticket/ticket1',  compact('productsSale', 'subtotal', 'totalDiscount', 'totalTaxes', 'totalAmount', 'config', 'sale', 'ticket'));
    $pdf->setPaper([0, 0, 226.772, 396.85], 'portrait');
    $dompdf = $pdf->getDomPDF();

    $GLOBALS['bodyHeight'] = 0;
    $dompdf->setCallbacks(
        array(
            'myCallbacks' => array(
                'event' => 'end_frame', 'f' => function ($frame) {
                    if (strtolower($frame->get_node()->nodeName) === "body") {
                        $padding_box = $frame->get_padding_box();
                        $GLOBALS['bodyHeight'] += $padding_box['h'];
                    }
                }
            )
        )
    );
    $dompdf->render();
    unset($dompdf);
    $pdf = PDF::loadView('ticket/ticket1',  compact('productsSale', 'subtotal', 'totalDiscount', 'totalTaxes', 'totalAmount', 'config', 'sale', 'ticket'));
    $pdf->setPaper([0, 0, 226.772, $GLOBALS['bodyHeight'] + 50], 'portrait');

      /*auditoria: start*/Pilates::setAudit("Impresión ticket id: $ticket->id"); /*auditoria: end*/
    return $pdf->stream("ticket_n$ticket->id.pdf");
  }

  public function downloadTicket($idSale = false)
  {

    $sale = Sale::where('sale.id', $idSale)->get(['*'])->first();
    if (empty($sale)) {
      return redirect('management_sale');
    }

    $productsSale = ProductSale::where('id_sale', $idSale)
      ->get(["*"])
      ->map(function ($product) {
        $product['total_product'] = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalByProductAmount($product));
        $product['price'] = Pilates::getFormatMoneyForDomPDF($product->price);
        $product['discount'] = Pilates::getFormatPercent($product->discount);
        $taxTmp = TaxSale::where('id_product_sale', $product->id)->get();
        $product['tax'] = $taxTmp->map(function ($tax) {
          if ($tax->type == 'percent') {
            $tax->tax = Pilates::getFormatPercent($tax->tax);
          }
          if ($tax->type == 'money') {
            $tax->tax = Pilates::getFormatMoneyForDomPDF($tax->tax);
          }
          return $tax;
        });

        return $product;
      })->values()->all();


    $idEmployee = auth()->user()->id;

    $ticket = Ticket::create([
      'date_create' => now(),
      'id_sale' => $idSale,
      'id_employee' => $idEmployee,
      'type' => 'ordinary'
    ]);

    $tmpProducts = ProductSale::where('id_sale', $idSale)->get();

    $subtotal = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalAmountSimple($tmpProducts));
    $totalDiscount = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalDiscount($tmpProducts));
    $totalTaxes = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalTaxes($tmpProducts));
    $totalAmount = Pilates::getFormatMoneyForDomPDF(Pilates::getTotalAmount($tmpProducts));

    $sale = Sale::find($idSale);
    $sale->increment('ticket_count', 1);
    $config = Configuration::first();
    //return view('ticket/ticket1', compact('productsSale','subtotal','totalDiscount','totalTaxes','totalAmount','config','sale','ticket'));


    $pdf = PDF::loadView('ticket/ticket1',  compact('productsSale', 'subtotal', 'totalDiscount', 'totalTaxes', 'totalAmount', 'config', 'sale', 'ticket'));
    $pdf->setPaper([0, 0, 226.772, 396.85], 'portrait');
    $dompdf = $pdf->getDomPDF();

    $GLOBALS['bodyHeight'] = 0;
    $dompdf->setCallbacks(
      array(
        'myCallbacks' => array(
          'event' => 'end_frame', 'f' => function ($infos) {
            $frame = $infos["frame"];
            if (strtolower($frame->get_node()->nodeName) === "body") {
              $padding_box = $frame->get_padding_box();
              $GLOBALS['bodyHeight'] += $padding_box['h'];
            }
          }
        )
      )
    );
    $dompdf->render();
    unset($dompdf);
    $pdf = PDF::loadView('ticket/ticket1',  compact('productsSale', 'subtotal', 'totalDiscount', 'totalTaxes', 'totalAmount', 'config', 'sale', 'ticket'));
    $pdf->setPaper([0, 0, 226.772, $GLOBALS['bodyHeight'] + 50], 'portrait');

 /*auditoria: start*/Pilates::setAudit("Descarga ticket id: $ticket->id"); /*auditoria: end*/
    return $pdf->download("ticket_n$ticket->id.pdf");
  }
}
