//$('.menu-restricted').remove();

$('.kt-menu__item--submenu').each(function() {
    var count=0;
    count=$(this).find("li.kt-menu__item").length;
    if(count<=0)
    $(this).remove();
});


$(document).ready(function() {
    $('ul.kt-menu__nav').
    find('li.kt-menu__item--active').
    parents('li').
    addClass('kt-menu__item--active kt-menu__item--open');
   
});

