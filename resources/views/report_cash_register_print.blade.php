<style>
*{
    font-size: 12px;
    font-family: 'opensans' !important;
}

html {
    /* margin: 0px; */
    margin-top: 40px;
    margin-bottom: 0px;
}
.table-heading{
width: 100%;
}
.input-heading{
width: 100%;
text-align: center;
border: solid 1px #c1cd29; 
padding: 1px;
border-radius: 5px;
display: inline-block;
}
.title-heading-input{
color: black;
text-align: justify;
width: 100%; 
display: inline-block;
margin: 5px;
}
.divi-part{
width: 100%;
margin-top: 10px;
margin-bottom: 10px;
background: #c1cd29;
padding: 5px;
color: #000;
text-align: center;
height: auto;
}
.container-master{
width: 100%;
padding: 0px;
margin: 0px;
}
.container-one{
position: relative;
display: inline-block;
width: 30%;
padding: 0px;
margin: 0px;    
float: left;
}
.container-two{
position: relative;
display: inline-block;
width: 60%;
padding: 0px;
margin: 0px;  
float: right;  
}
.table-counts{
width: 100%;
}
.table-counts td{
padding: 5px;
}
.table-counts td,.table-counts tr{
border:solid 1px #c1cd29;
}
.container-result{
text-align: center;
border: solid 1px #c1cd29; 
border-radius: 5px;
width: 80%;
min-height: 150px;
position: relative;
display: inline-block;
margin: 10px;
margin: auto;
margin-top: 15px;
left: 0;
right: 0;
}
.status-green{
color: green;
font-size: 18px;
}
.status-red{
color: red;
font-size: 18px;
}
</style>

<div class="heading-container">

<table class="table-heading">
<tr>
<td></td>
<td></td>
<td><div class="title-heading-input">Fecha</div><br><input class="input-heading" type="text" value="{!!$date!!}"></td>
</tr>
<tr>
<td><div class="title-heading-input">Ingresos Tarjeta</div><br><input class="input-heading" type="text" value="{!!$earnings_tj!!}"></td>
<td><div class="title-heading-input">Ingresos Metalico</div><br><input class="input-heading" type="text" value="{!!$earnings_metalic!!}"></td>
<td><div class="title-heading-input">Total Ingresos</div><br><input class="input-heading" type="text" value="{!!$earnings_total!!}"></td>
</tr>
</table>
<div class="divi-part">Conteo</div>
<div class="container-master">
<div class="container-one">
<table  cellspacing="0" class="table-counts">
@foreach($fields_count as $field)
<tr><td>{!!$field['text']!!}</td><td style="text-align:center;">{!!$field['value_start']!!}</td></tr>
@endforeach
</table>
</div>
<div class="container-two">
        <div class="title-heading-input">Saldo Inicial</div>
        <br>
        <input class="input-heading" type="text" value="{!!$initial_balance!!}">
        <br>
        <div class="title-heading-input">Recibos de gastos</div>
        <br>
        <input class="input-heading" type="text" value="{!!$receipts_expenses!!}">
        <br>
        <br>
        <div class="title-heading-input" style="text-align:center;">Resultado</div>
        <br>
        <div class="container-result">
        @if($status=='false')
         <h4 class="status-red">!Arqueo Incorrecto!</h4>
         @else
         <h4 class="status-green">!Arqueo Correcto!</h4>
         @endif
        </div>
</div>
</div>

</div>


<!-- end:: Content -->

