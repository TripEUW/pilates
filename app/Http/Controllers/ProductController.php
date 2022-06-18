<?php

namespace App\Http\Controllers;

use App\Helpers\Pilates;
use App\Http\Requests\ValidationDeleteProduct;
use App\Http\Requests\ValidationProduct;
use App\Models\Product;
use App\Models\Tax;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        return view("management_product");
    }



    public function store(ValidationProduct $request)
    {
        $taxDefaultName = 'IGIC';
        $taxTypeDefault = 'percent';
        
        $request->merge(['suscription' => (($request->has("suscription_status"))?"true":"false")]);
        $product = Product::create(array_filter($request->except('price_all', 'tax','suscription_status')));
        Tax::create(['name' =>  $taxDefaultName, 'tax' => (($request->tax)??0), 'type' => $taxTypeDefault, 'id_product' => $product->id]);

         /*auditoria: start*/Pilates::setAudit("Alta producto id: $product->id "); /*auditoria: end*/
        return redirect('management_product')->with('success', 'Producto creado con éxito.');
    }



    public function update(ValidationProduct $request)
    {
        $request->merge(['suscription' => (($request->has("suscription_status"))?"true":"false")]);
        Product::findOrFail($request['id'])->update($request->except('price_all', 'tax', 'id_tax','suscription_status'));
        Tax::findOrFail($request->id_tax)->update(['tax' => (($request->tax)??0)]);
         /*auditoria: start*/Pilates::setAudit("Actualización producto id: $request->id"); /*auditoria: end*/
        return redirect('management_product')->with('success', 'Producto actualizado con éxito.');
    }


    public function destroy(ValidationDeleteProduct $request)
    {
        $errors = 0;
        $cantSuccsess = 0;
        $idsProducts = $request['id'];
        foreach ($idsProducts as $key => $id) {

            if (Product::where('id', $id)->delete()) {
                $cantSuccsess++;
            } else {
                $errors++;
            }
        }

         /*auditoria: start*/Pilates::setAudit("Baja producto id: $request->id"); /*auditoria: end*/
        return $cantSuccsess <= 1 ?
            redirect('management_product')->with('success', $cantSuccsess . ' producto eliminado con éxito.')
            :
            redirect('management_product')->with('success', $cantSuccsess . ' productos eliminados con éxito.');
    }

    public function dataTable(Request $request)
    {

        $product = new Product();
        $products = $product->getProductDataTable($request);
        return response()->json($products);
    }
}
