<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationDeleteSale;
use App\Http\Requests\ValidationGenerateInvoice;
use App\Models\Client;
use App\Models\Configuration;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\TaxSale;
use App\Rules\RuleAmountSaleMixType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF;

class SaleController extends Controller
{

    public function index()
    {
        return view("administration_sale");
    }
    public function indexManagment()
    {
        return view("management_sale");
    }


    public function store(Request $request)
    {

        $rules = [
            'id'  => 'required|exists:client,id',
            'last_name' => 'required_with:invoice',
            'cif_nif' => 'required_with:invoice',
            'name' => 'required_with:invoice',
            'email' => 'nullable|required_with:invoice|email',
            'tel' => 'nullable',
            'address' => 'required_with:invoice',
            'method_payment' => 'required',
            'products' => 'required',
            'invoice' => 'nullable',
            'ticket' => 'nullable',
            'amount' => 'required|numeric|min:1|required_with:cant_cash'
        ];

        if(!empty($request->input('method_payment'))){
        if($request->method_payment=="mix"){
            $rules = [
                'cant_cash'  => 'required|numeric|min:1|required_with:cant_tj',
                'cant_tj' => ['required','numeric','min:1', new RuleAmountSaleMixType($request->amount,$request->cant_tj,$request->cant_cash)],
            ];
        }
        }

        $messages = [
            'id.required' => 'Necesita seleccionar un cliente antes de completar una venta.',
            'method_payment.required' => 'Necesita seleccionar el método de pago antes de completar una venta.',
            'products.required' => 'Debe agregar al menos 1 producto para completar la venta.',
            'last_name.required_with' => 'El campo apellidos es necesario para emitir una factura.',
            'name.required_with' => 'El campo nombre es necesario para emitir una factura.',
            'email.required_with' => 'El campo email es necesario para emitir una factura.',
            'tel.required_with' => 'El campo teléfono es necesario para emitir una factura.',
            'address.required_with' => 'El campo dirección es necesario para emitir una factura.',
            'cif_nif.required_with' => 'El campo CIF o NIF es necesario para emitir una factura.'
        ];


        $products = $request->input('products');
        if (isset($products)) {
            foreach ($products as $key => $product) {
                $rules["products.$key.cant"] = 'integer|min:1';
                $rules["products.$key.discount"] = 'numeric|min:0';

                $messages["products.$key.cant.integer"] = "La cántidad del producto #{$product['product']['id']} debe ser un número entero.";
                $messages["products.$key.discount.numeric"] = "El descuento del producto #{$product['product']['id']} debe ser un valor numérico.";
                $messages["products.$key.cant.min"] = "La cántidad del producto #{$product['product']['id']} debe ser mayor o igual a 1.";
                $messages["products.$key.discount.min"] = "El descuento del producto  #{$product['product']['id']} debe ser igual o mayor a 0.";
            }
        }

        $attr=[
            'cant_cash' => 'cántidad en efectivo',
            'cant_tj' => 'cántidad con tarjeta'
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $attr);

        if ($request->ajax()) {

            if ($validator->passes()) {
                $totalTj=0;
                $totalCash=0;

                if($request->method_payment=="mix"){
                    $totalTj=$request->cant_tj;
                    $totalCash=$request->cant_cash;
                }else if($request->method_payment=="cash"){
                    $totalTj=0;
                    $totalCash=$request->amount;
                }else if($request->method_payment=="card"){
                    $totalTj=$request->amount;
                    $totalCash=0;
                }

                $idEmployee = auth()->user()->id;
                $sale = Sale::create([
                    'id_client' => $request->id,
                    'id_employee' => $idEmployee,
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'cif_nif' => $request->cif_nif,
                    'tel' => $request->tel,
                    'email' => $request->email,
                    'address' => $request->address,
                    'type_payment' => $request->method_payment,

                    'cant_tj' => floatval($totalTj),
                    'cant_cash' => floatval($totalCash),

                    'type_emission' => ($request->invoice) ? 'invoice' : 'ticket',
                    'invoice_count' => ($request->invoice) ? 1 : 0,
                    'ticket_count' => ($request->ticket) ? 1 : 0
                ]);

                $sessionsMachineAdd = 0;
                $sessionsFloorAdd = 0;
                $sessionsIndividualAdd = 0;
                $flagSuscription=false;
                foreach ($products as $product) {

                    $sessionsMachineAdd += intval($product['product']['sessions_machine']);
                    $sessionsFloorAdd += intval($product['product']['sessions_floor']);
                    $sessionsIndividualAdd += intval($product['product']['sessions_individual']);

                    $productTmp=Product::where('id',$product['product']['id'])->get(['suscription'])->first();
                    $product_sale = ProductSale::create([
                        'id_sale' => $sale->id,
                        'id_product' => $product['product']['id'],
                        'name' => $product['product']['name'],
                        'price' => $product['product']['price'],
                        'sessions_machine' => $product['product']['sessions_machine'],
                        'sessions_floor' => $product['product']['sessions_floor'],
                        'sessions_individual' => $product['product']['sessions_individual'],
                        'observation' => $product['product']['observation'],
                        'cant' => $product['cant'],
                        'suscription' => $productTmp->suscription,
                        'discount' =>  $product['discount'] ?? 0,
                    ]);

                    if($productTmp->suscription=="true")
                    $flagSuscription=true;

                    $taxs = $product['product']['tax'];
                    foreach ($taxs as $tax) {
                        TaxSale::create([
                            'name' => $tax['name'],
                            'tax' => $tax['tax'],
                            'type' => $tax['type'],
                            'id_product_sale' => $product_sale->id
                        ]);
                    }
                }
                $invoice = false;
                if ($request->invoice) {
                    $config = Configuration::first();
                    $num_factura = ($config->num_factura + 1);
                    $invoice = Invoice::create([
                        'date_create' => now(),
                        'id_sale' => $sale->id,
                        'id_employee' => $idEmployee,
                        'type' => 'ordinary',
                        'code' => ('F'.date('y').'/'.str_pad($num_factura, 5, "0", STR_PAD_LEFT)),
                    ]);
                    $config->num_factura = $num_factura;
                    $config->save();
                }

                $client = Client::find($request->id);
                $client->increment('sessions_machine', $sessionsMachineAdd);
                $client->increment('sessions_floor', $sessionsFloorAdd);
                $client->increment('sessions_individual', $sessionsIndividualAdd);
                if($flagSuscription)
                $client->update(["suscription"=>"true"]);

                $path_download = route(
                    ($invoice != false) ?
                        "administration_billing_invoice_print"
                        :
                        "administration_billing_ticket_print",
                    ["id" => $sale->id]
                );

                  /*auditoria: start*/Pilates::setAudit("Alta venta id: ".$sale->id); /*auditoria: end*/
                return response()->json(['success' => 'Venta hecha con éxito', 'error' => false, 'invoice' => $invoice, 'sale' => $sale, 'path_download' => $path_download]);
            } else {
                return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
            }
        } else {
            abort(404);
        }
    }



    public function destroy(ValidationDeleteSale $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $idsSales = $request['id'];
        foreach ($idsSales as $key => $id) {

            if (Sale::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }


                  /*auditoria: start*/Pilates::setAudit("Baja venta ids: ".implode(', ', $idsSales)); /*auditoria: end*/
        return $cantSuccsess <= 1 ?
            redirect('management_sale')->with('success', $cantSuccsess . ' venta eliminada con éxito.')
            :
            redirect('management_sale')->with('success', $cantSuccsess . ' ventas eliminadas con éxito.');
    }


    public function dataTable(Request $request)
    {
        $sale = new Sale();
        $sales = $sale->getSalesDataTable($request);
        return response()->json($sales);
    }

    public function generateInvoiceIndex($idSale = false)
    {
        $sale = Sale::where('id', $idSale)->firstOrFail();
        if ($sale->invoice_count > 0) {
            return redirect('management_sale')->with('success', 'Ahora puede imprimir o descargar la factura.');
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
        $tmpProducts = ProductSale::where('id_sale', $idSale)->get();
        $totalAmount = Pilates::getFormatMoney(Pilates::getTotalAmount($tmpProducts));
        return view('management_sale_generate_invoice', compact('sale', 'productsSale', 'totalAmount'));
    }

    public function generateInvoice(Request $request)
    {

        $idSale = $request->id_sale;
        $sale = Sale::where('id', $idSale)->firstOrFail();
        if ($sale->invoice_count > 0) {
            return response()->json(['success' => false, 'error' => ['La factura ya fue creada, no puede crear 2 facturas para la misma venta.']]);
        }

        $rules = [
            'last_name' => 'required',
            'cif_nif' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'tel' => 'nullable',
            'address' => 'required'
        ];

        $messages = [
            'last_name.required' => 'El campo apellidos es necesario para emitir una factura.',
            'name.required' => 'El campo nombre es necesario para emitir una factura.',
            'email.required' => 'El campo email es necesario para emitir una factura.',
            'tel.required' => 'El campo teléfono es necesario para emitir una factura.',
            'address.required' => 'El campo dirección es necesario para emitir una factura.',
            'cif_nif.required' => 'El campo CIF o NIF es necesario para emitir una factura.'
        ];



        $validator = Validator::make($request->all(), $rules, $messages);

        if ($request->ajax()) {

            if ($validator->passes()) {

                $idEmployee = auth()->user()->id;

                $sale = Sale::findOrFail($idSale)->update([
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'cif_nif' => $request->cif_nif,
                    'tel' => $request->tel,
                    'email' => $request->email,
                    'address' => $request->address,
                    'invoice_count' => 1,
                ]);

                $invoice = Invoice::create([
                    'date_create' => now(),
                    'id_sale' => $idSale,
                    'id_employee' => $idEmployee,
                    'type' => 'ordinary'
                ]);


                  /*auditoria: start*/Pilates::setAudit("Alta factura id: $invoice->id"); /*auditoria: end*/
                return response()->json(['success' => 'Factura creada con éxito.', 'error' => false, 'invoice' => $invoice, 'sale' => $sale, 'redirect' => route('administration_billing_invoice_print', ['id' => $idSale])]);
            } else {
                return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
            }
        } else {
            abort(404);
        }
    }
}
