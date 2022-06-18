<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Pilates;
use Carbon\Carbon;
use DateTime;

class Product extends Model
{
  protected $table = 'product';
  protected $guarded = ['id'];

  public function getProductDataTable(Request $request)
  {
    $columns = array(
      0 => 'id_select',
      1 => 'id',
      2 => 'name',
      3 => 'type',
      4 => 'sessions_individual',
      5 => 'sessions_floor',
      6 => 'sessions_machine',
      7 => 'observation',
      8 => 'tax',
      9 => 'price',
      10 => 'price_end',
      11 => 'created_at',
    );

    $totalData = Product::get(['*'])
      ->count();

    $totalFiltered = $totalData;
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');
    $dir = ($dir == 'desc') ? true : false;


    $products = [];
    if (empty($request->input('search.value'))) {
      if ($limit == -1) {
        $products = Product::get(['*'])->map(function ($product) {
            return $this->analizeFilterProductDataTable($product);
          })->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
      } else {
        $products = Product::get(['*'])->map(function ($product) {
            return  $this->analizeFilterProductDataTable($product);
          })
          ->skip($start)->take($limit)
          ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
      }
    } else {
      $search = $request->input('search.value');
      if ($limit == -1) {
        $products =  Product::get(['*'])->map(function ($product) {
            return   $this->analizeFilterProductDataTable($product);
          })
          ->filter(function ($product) use ($search, $columns) {
            $item = false;
            foreach ($columns as $colum)
              if (stristr($product[$colum], $search))
                $item = $product;
            return $item;
          })
          ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
      } else {

        $products =  Product::get(['*'])->map(function ($product) {
            return $this->analizeFilterProductDataTable($product);
          })
          ->filter(function ($product) use ($search, $columns) {
            $item = false;
            foreach ($columns as $colum)
              if (stristr($product[$colum], $search))
                $item = $product;
            return $item;
          })
          ->skip($start)->take($limit)
          ->sortBy($order, SORT_NATURAL | SORT_FLAG_CASE, $dir)->values()->all();
      }

      $totalFiltered = Product::get(['*'])
        ->map(function ($product) {
          return $this->analizeFilterProductDataTable($product);
        })
        ->filter(function ($product) use ($search, $columns) {
          $item = false;
          foreach ($columns as $colum) {
            if (stristr($product[$colum], $search))
              $item = $product;
          }
          return $item;
        })
        ->count();
    }



    $result = [
      'iTotalRecords'        =>  $totalData,
      'iTotalDisplayRecords' => $totalFiltered,
      'aaData'               =>  $products
    ];

    return $result;
  }

  function analizeFilterProductDataTable($product)
  {
    

    $productTmp = $product;

  
    $product['id_select'] = $product->id;
    $product['type'] = ($product->suscription=="false" || $product->suscription=="" || $product->suscription == NULL)?"Básico":"Suscripción";
    $taxTmp = Tax::where('id_product', $product->id)->get();

    $productTmp['price_end'] = Pilates::getPriceWithTaxes($productTmp->price, $taxTmp);
    $productTmp['tax'] = $taxTmp;
    $product['actions'] = json_decode($productTmp);

    $product['tax'] = $taxTmp->map(function ($tax) {
      if ($tax->type == 'percent') {
        $tax->tax = Pilates::getFormatPercent($tax->tax);
      }
      if ($tax->type == 'money') {
        $tax->tax = Pilates::getFormatMoney($tax->tax);
      }
      return $tax;
    });
    $product['created_at_2'] = Carbon::createFromFormat('Y-m-d H:i:s', strval($product->created_at))->format('d/m/Y');
    $product['tax'] = $product['tax'][0]->tax;
    $product->price = Pilates::getFormatMoney($product->price);
    $product['price_end'] = Pilates::getFormatMoney(Pilates::getPriceWithTaxes($product->price, $taxTmp));
   
    return $product;
  }
}
