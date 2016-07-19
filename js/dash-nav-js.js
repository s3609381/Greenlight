$(document).ready(function() {
    $('.nav-toggle').click(function() {
        $(this).next('#toggleNav').toggle(300);
        $(this).children('span').toggleClass('glyphicon-plus glyphicon-minus');
    });

});