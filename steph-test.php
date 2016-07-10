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
    
  <form id="div_builder">
   <input type="text" class="changeD" id="width"/>
   <input type="text" class="changeD" id="height"/>
   </form>
   
<div id="test" style="background-color:red">hello</div>

<script type="text/javascript">
$(".changeD").keyup (function () {
var theval = Number($(this).val());
if(!isNaN(theval)){
$("#test").css( this.id, theval);
}
});
</script>


</body>

</html>