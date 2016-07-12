<!DOCTYPE html>

<html lang="en">

<head>
    <!-- icons (.png for apple and .ico for favicon) -->
    <link rel="apple-touch-icon" href="">
    <link rel="shortcut icon" href="">

    <title>Steph Testing Stuff</title>

    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- js / jquery -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/dash-nav-js.js"></script>
</head>

<body>
    
<textarea id='text'></textarea>
<div id='target'></div>

<br/>

<textarea id='text1'></textarea>
<div id='target1'></div>

<script type="text/javascript">

$('#text').keyup(function() {
    var keyed = $(this).val().replace(/\n/g, '<br/>');
    $("#target").html(keyed);
});

</script>


</body>

</html>