
<link href="{{asset("assets")}}/css/ticket.css" rel="stylesheet" type="text/css" />
<div class="container-ticket">
<table class="table-head-ticket">
<tr>
{{-- head.part0 --}}
<td  colspan="2" class="tick-head-part0"><img class="logo-ticket" src="{{asset("assets/images")}}/logo-ticket.jpg"></td>
</tr>
<tr>
<td class="tick-head-part1" colspan="2">
<h1 class="tick-head-part1-name">{{ ($config->name_entity)??"No configurado" }}</h1>
<p class="tick-head-part1-address">{{ ($config->address)??"No configurado" }}</p>
<p class="tick-head-part1-address">NIF: {{ ($config->cif)??"No configurado" }}</p>
</td>
</tr>
<tr>
<td class="tick-head-part2">
<p class="tick-head-part2-date">{{date("d-m-Y g:i:s A")}}</p>
</td>
<td class="tick-head-part2">
<p class="tick-head-part2-num">{{ "NO.".$ticket->id }}</p>
</td>
</tr>
</table>

<table  cellspacing="0" class="table-detail">

<thead>
<tr>
<th>Concepto</th><th>Unidades</th> <th>Precio</th>
</tr>
</thead>

<tbody>
  
    @foreach ($productsSale as $product)
      <tr>
          <td>{{$product->name}}</td>
          <td>{{ $product->cant }}</td>
          <td colspan="2">{!! $product->price !!}</td>
      </tr>
    @endforeach
</tbody>

</table>
<div class="div-line"></div>
<table class="tick-table-totals">
<tr><td>Subtotal:</td><td>{!!$subtotal!!}</td></tr>
<tr><td>Descuento:</td><td>- {!!$totalDiscount!!}</td></tr>
<tr><td>IGIC:</td><td>{!!$totalTaxes!!}</td></tr>
<tr><td>Total a pagar:</td><td>{!!$totalAmount!!}</td></tr>
</table>
<div class="div-line"></div>
<table class="tick-table-end">
    <tr><td>Empleado:</td><td>{{ auth()->user()->name." ".auth()->user()->last_name}}</td></tr>
    <tr><td>Cliente:</td><td>{{ $sale->name." ".$sale->last_name}}</td></tr>
</table>
<br>
<h1 class="tanks">GRACIAS POR SU COMPRA</h1>
</div>