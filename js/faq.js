$(document).ready(function() {
    $('.panel-heading').click(function() {
        $(this).next('.panel-body-hidden').toggle(300);
        
        $(this).children('span').toggleClass('glyphicon-plus glyphicon-minus');
    });
});