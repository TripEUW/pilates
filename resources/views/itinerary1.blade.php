
<link href="{{asset("assets")}}/css/itinerary.css" rel="stylesheet" type="text/css" />




<?php 
$cant=count($groupsItineraries);
foreach ($groupsItineraries as $key => $groupItineraries) { 
?>

<?php if(count($groupItineraries)==3) { ?>

<?php 
$indexRoot=0;

if(count($groupItineraries[0]['items_employee']) >= count($groupItineraries[1]['items_employee']) && count($groupItineraries[0]['items_employee']) >= count($groupItineraries[2]['items_employee']) ){
    $indexRoot=0;
}else if(count($groupItineraries[1]['items_employee']) >= count($groupItineraries[0]['items_employee']) && count($groupItineraries[0]['items_employee']) >= count($groupItineraries[2]['items_employee']) ){
    $indexRoot=1;
}else if(count($groupItineraries[2]['items_employee']) >= count($groupItineraries[0]['items_employee']) && count($groupItineraries[0]['items_employee']) >= count($groupItineraries[1]['items_employee']) ){
    $indexRoot=2;
}

?>


{{-- start: template 3 column --}} 
<table cellspacing="0" border="1">
<tr> <td colspan="3" class="date">{{$groupItineraries[0]['date']}}</td> {{-- || --}} <td colspan="3" class="date">{{$groupItineraries[1]['date']}}</td>{{-- || --}} <td colspan="3" class="date">{{$groupItineraries[2]['date']}}</td></tr>
<tr> <td colspan="3" class="name-employee">{{$groupItineraries[0]['employee_name']}} </td> {{-- || --}}  <td colspan="3" class="name-employee">{{$groupItineraries[1]['employee_name']}} </td>{{-- || --}}  <td colspan="3" class="name-employee">{{$groupItineraries[2]['employee_name']}} </td></tr>
<tr>
<td class="title-hours">Horas</td><td class="title-room">Sala</td><td class="title-clients">Clientes</td> {{-- || --}} <td class="title-hours">Horas</td><td class="title-room">Sala</td><td class="title-clients">Clientes</td>{{-- || --}} <td class="title-hours">Horas</td><td class="title-room">Sala</td><td class="title-clients">Clientes</td></tr>
<?php foreach ($groupItineraries[$indexRoot]['items_employee'] as $key => $itemEmployee) { ?>
<tr>
<td class="hours">{!!(isset($groupItineraries[0]['items_employee'][$key]['start']))?$groupItineraries[0]['items_employee'][$key]['start']."<br> a <br>":''!!} {{(isset($groupItineraries[0]['items_employee'][$key]['end']))?$groupItineraries[0]['items_employee'][$key]['end']:''}}</td><td class="room">{{(isset($groupItineraries[0]['items_employee'][$key]['room_name']))?$groupItineraries[0]['items_employee'][$key]['room_name']." Grupo ":''}}{{ (isset($groupItineraries[0]['items_employee'][$key]['group_name']))?$groupItineraries[0]['items_employee'][$key]['group_name']:'' }}</td><td class="clients">

<?php if(isset($groupItineraries[0]['items_employee'][$key]['clients'])){ foreach ($groupItineraries[0]['items_employee'][$key]['clients'] as  $client) { ?>
{{$client['name']}} {{$client['last_name']}}. <br>
<?php }} ?>
{{-- part 2 --}}
<td class="hours">{!!(isset($groupItineraries[1]['items_employee'][$key]['start']))?$groupItineraries[1]['items_employee'][$key]['start']."<br> a <br>":''!!}{{(isset($groupItineraries[1]['items_employee'][$key]['end']))?$groupItineraries[1]['items_employee'][$key]['end']:''}}</td><td class="room">{{(isset($groupItineraries[1]['items_employee'][$key]['room_name']))?$groupItineraries[1]['items_employee'][$key]['room_name']." Grupo ":''}}{{ (isset($groupItineraries[1]['items_employee'][$key]['group_name']))?$groupItineraries[1]['items_employee'][$key]['group_name']:'' }}</td><td class="clients">

<?php if(isset($groupItineraries[1]['items_employee'][$key]['clients'])){ foreach ($groupItineraries[1]['items_employee'][$key]['clients'] as  $client) { ?>
{{$client['name']}} {{$client['last_name']}}. <br>
<?php }} ?>
{{-- part 3 --}}
<td class="hours">{!!(isset($groupItineraries[2]['items_employee'][$key]['start']))?$groupItineraries[2]['items_employee'][$key]['start']."<br> a <br>":''!!}{{(isset($groupItineraries[2]['items_employee'][$key]['end']))?$groupItineraries[2]['items_employee'][$key]['end']:''}}</td><td class="room">{{(isset($groupItineraries[2]['items_employee'][$key]['room_name']))?$groupItineraries[2]['items_employee'][$key]['room_name']." Grupo ":''}}{{ (isset($groupItineraries[2]['items_employee'][$key]['group_name']))?$groupItineraries[2]['items_employee'][$key]['group_name']:'' }}</td><td class="clients">

<?php if(isset($groupItineraries[2]['items_employee'][$key]['clients'])){ foreach ($groupItineraries[2]['items_employee'][$key]['clients'] as  $client) { ?>
{{$client['name']}} {{$client['last_name']}}. <br>
<?php }} ?>

</td>

</tr>

<?php 
}
?>
</table>
{{-- end: template 3 column --}} 


<?php }else if(count($groupItineraries)==2){ ?>
<?php 
$indexRoot=0;
if(count($groupItineraries[0]['items_employee'])>=$groupItineraries[1]['items_employee']){ $indexRoot=0;}else{$indexRoot=1;}
?>


{{-- start: template 2 column --}} 
<table cellspacing="0" border="1">
<tr> <td colspan="3" class="date">{{$groupItineraries[0]['date']}}</td> {{-- || --}} <td colspan="3" class="date">{{$groupItineraries[1]['date']}}</td></tr>
<tr> <td colspan="3" class="name-employee">{{$groupItineraries[0]['employee_name']}} </td> {{-- || --}}  <td colspan="3" class="name-employee">{{$groupItineraries[1]['employee_name']}} </td></tr>
<tr>
<td class="title-hours">Horas</td><td class="title-room">Sala</td><td class="title-clients">Clientes</td> {{-- || --}} <td class="title-hours">Horas</td><td class="title-room">Sala</td><td class="title-clients">Clientes</td></tr>
<?php foreach ($groupItineraries[$indexRoot]['items_employee'] as $key => $itemEmployee) { ?>
<tr>
<td class="hours">{!!(isset($groupItineraries[0]['items_employee'][$key]['start']))?$groupItineraries[0]['items_employee'][$key]['start']."<br> a <br>":''!!} {{(isset($groupItineraries[0]['items_employee'][$key]['end']))?$groupItineraries[0]['items_employee'][$key]['end']:''}}</td><td class="room">{{(isset($groupItineraries[0]['items_employee'][$key]['room_name']))?$groupItineraries[0]['items_employee'][$key]['room_name']." Grupo ":''}}{{ (isset($groupItineraries[0]['items_employee'][$key]['group_name']))?$groupItineraries[0]['items_employee'][$key]['group_name']:'' }}</td><td class="clients">

<?php if(isset($groupItineraries[0]['items_employee'][$key]['clients'])){ foreach ($groupItineraries[0]['items_employee'][$key]['clients'] as  $client) { ?>
{{$client['name']}} {{$client['last_name']}}. <br>
<?php }} ?>
{{-- part2 --}}
<td class="hours">{!!(isset($groupItineraries[1]['items_employee'][$key]['start']))?$groupItineraries[1]['items_employee'][$key]['start']."<br> a <br>":''!!}{{(isset($groupItineraries[1]['items_employee'][$key]['end']))?$groupItineraries[1]['items_employee'][$key]['end']:''}}</td><td class="room">{{(isset($groupItineraries[1]['items_employee'][$key]['room_name']))?$groupItineraries[1]['items_employee'][$key]['room_name']." Grupo ":''}}{{ (isset($groupItineraries[1]['items_employee'][$key]['group_name']))?$groupItineraries[1]['items_employee'][$key]['group_name']:'' }}</td><td class="clients">

<?php if(isset($groupItineraries[1]['items_employee'][$key]['clients'])){ foreach ($groupItineraries[1]['items_employee'][$key]['clients'] as  $client) { ?>
{{$client['name']}} {{$client['last_name']}}. <br>
<?php }} ?>
 
</td>

</tr>

<?php 
}
?>
</table>
{{-- end: template 2 column --}} 


<?php }else if(count($groupItineraries)==1){ ?>
{{-- start: template 1 column --}} 
<table cellspacing="0" border="1">
<tr> <td colspan="3" class="date">{{$groupItineraries[0]['date']}}</td></tr>
    <tr> <td colspan="3" class="name-employee">{{$groupItineraries[0]['employee_name']}}</td></tr>
    <tr><td class="title-hours">Horas</td><td class="title-room">Sala</td><td class="title-clients">Clientes</td></tr>
<?php foreach ($groupItineraries[0]['items_employee'] as $key => $itemEmployee) { ?>
<tr><td class="hours">{{$groupItineraries[0]['items_employee'][$key]['start']}}<br> a <br>{{$groupItineraries[0]['items_employee'][$key]['end']}}</td><td class="room">{{$groupItineraries[0]['items_employee'][$key]['room_name']}} Grupo {{$groupItineraries[0]['items_employee'][$key]['group_name']}}</td><td class="clients">
    
<?php foreach ($groupItineraries[0]['items_employee'][$key]['clients'] as  $client) { ?>
{{$client['name']}} {{$client['last_name']}}. <br>
<?php } ?>

</td></tr>
    
<?php 
}
?>
</table>
{{-- end: template 1 column --}} 
<?php } ?>
<br>

<?php if(($key+1)<$cant){ ?>
    {{-- <div class="break-now"></div> --}}
<?php } ?>

<?php } ?>