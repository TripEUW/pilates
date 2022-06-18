
<link href="{{asset("assets")}}/css/invoice_simple.css" rel="stylesheet" type="text/css" />
<div class="container-invoice">
<table class="table-head-invoice">
<tr>
{{-- head.part0 --}}
<td class="inv-head-part0"><img class="logo-invoice" src="{{asset("assets/images")}}/logo-dashboard.jpg"></td>
{{-- head.part1 --}}
<td class="inv-head-part1">
<h1 class="inv-head-part1-name">{{ ($config->name_entity)??"No configurado" }}</h1>
<p class="inv-head-part1-address">NIF: {{ ($config->cif)??"No configurado" }}</p>
  <p class="inv-head-part1-address">{{ ($config->address)??"No configurado" }}</p></td>
  <td></td>
{{-- head.part2 --}}
<td class="inv-head-part1-1" style="font-size:12px">
<div class="sub-rigth" style="float:right">
  <div style="font-size:12px; font-weight: bold;">FACTURA</div>
  <div style="font-size:12px">Fecha</div>
  <div class="div-line"></div>
  <div style="font-size:12px">{{$invoiceSale->date_create}}</div>
  <div style="font-size:12px">N.de Factura</div>
  <div class="div-line"></div>
  <div >{{$invoiceSale->code}}</div>
</div>
</td>
{{-- sub-head.part0 --}}
<td class="inv-head-part1-1">
  <h1 class="inv-sub-head-part0-name">CLIENTE</h1>
    <p class="inv-sub-head-part0-address">{{$invoiceSale->name.' '.$invoiceSale->last_name}}</p>
  <p class="inv-sub-head-part0-address">NIF: {{$invoiceSale->cif_nif}}</p>
  <p class="inv-sub-head-part0-address">{{$invoiceSale->address}}</p>
</td>
</tr>
</table>
<br>
<div class="div-line"></div>
<table class="table-sub-head-invoice">
<tr>

</tr>
</table>
{{-- inv-detail-table --}}
<table class="inv-detail-table" cellspacing="0">
  <thead>
    <tr>
      <th style="width: 5%;">#</th>
      <th style="width: 25%;">Producto</th>
      <th style="width: 10%;">Unidades</th>
      <th style="width: 15%;">Precio</th>
      <th style="width: 15%;">Descuento</th>
      <th style="width: 15%;">IGIC</th>
      <th style="width: 15%; text-align:right">Total</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($productsSale as $product)
    <tr>
        <td>{{$product->id}}</td>
          <td>{{$product->name}}</td>
          <td>{{$product->cant}}</td>
          <td>{!! $product->price !!}</td>
          <td>{{$product->discount}}</td>
          <td>{{$product->tax[0]->tax??'0.00 %'}}</td>
          <td style="text-align:right">{!! $product->total_product !!}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<table class="inv-table-totals">
<tr><td>Subtotal:</td><td>{!!$subtotal!!}</td></tr>
<tr><td>Descuento:</td><td>- {!!$totalDiscount!!}</td></tr>
<tr><td>IGIC:</td><td>{!!$totalTaxes!!}</td></tr>
<tr><td>Total:</td><td>{!!$totalAmount!!}</td></tr>
</table>
</div>
