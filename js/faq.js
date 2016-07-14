/*
File name = faq.js
Include in faq.php 
*/
<head1>

</head1> 
/*
elements:
*/
<script src="/js/faq.php"></script>
$(document).ready(function() {
    $('.nav-toggle').click(function() {
        //get collapse content selector
        var collapse_content_selector = $(this).attr('href');

        //the links to be shown and hidden
        var toggle_switch = $(this);
        $(collapse_content_selector).toggle(function() {
            if ($(this).css('display') == 'none') {
                //change the button label to be '+'
                toggle_switch.html('Create Light <span class="glyphicon glyphicon-plus pull-right" aria-hidden="true"></span>');
            }
            else {
                //change the button label to be '-'
                toggle_switch.html('Create Light <span class="glyphicon glyphicon-minus pull-right" aria-hidden="true"></span>');
            }
        });
    });

});
/*
Must also have the following added above each panel-body:
*/
<span>
[Add Style Here If Needed] class='glyphicon glyphicon-plus'>
</span>
