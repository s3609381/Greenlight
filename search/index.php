<!DOCTYPE html>

<?php
    session_start();
   
    include("../config.php");
   
    //Variable $searchField is created from the following line
    parse_str($_SERVER["QUERY_STRING"]);
   
    $outputResults = '';
    
    $userId = $_SESSION['user_id'];
   
    if($searchField != null){
        $searchField = trim($searchField);
        $searchField = strtolower($searchField);
        $searchField = htmlspecialchars($searchField, ENT_NOQUOTES);
        $searchField = str_ireplace('"', "", $searchField);
       
        $commonWords = array(" the ", " a ", " i ", " an ", " am ", " it ", " in ", " is ", " if ", ", ", ". ", "! ", "? ");
        $escapes = array("'", ";", ":", "(", ")", "[", "]", "{", "}", "|", "`", "~");
        $searchField = str_ireplace($commonWords, " ", $searchField);
        
        foreach($escapes as $escape){
            if($escape == "'"){
                $searchField = str_replace($escape, "\\".$escape, $searchField);
            }
            else{
                $searchField = str_replace($escape, "", $searchField);
            }
        }
        
        $searchTerms = split(' ', $searchField);
        
        //Example: SELECT * FROM `tblLights` WHERE (LightTitle LIKE '%'.$term.'%' OR Description LIKE '%'.$term.'%') AND LightDeleted = false AND (Public = true OR (UserID = '.$userId.' AND Public = false))
        $query = 'SELECT LightID, UserID, LightTitle, Description, ColourID, State FROM tblLights WHERE ';
        $i = 0;
   
        //Add each term to filter results where the term is an exact match, not a partial match. I.e. Test does not return when Testing is found.
        foreach ($searchTerms as $term){
            if($i == 0){
                $query .= '(LightTitle LIKE \'% '.$term.' %\' OR Description LIKE \'% '.$term.' %\' OR LightTitle LIKE \'%'.$term.'%\' OR Description LIKE \'%'.$term.'%\' ';
           
                $i++;
            }
            else{
                $query .= 'OR LightTitle LIKE \'% '.$term.' %\' OR Description LIKE \'% '.$term.' %\' OR LightTitle LIKE \'%'.$term.'%\' OR Description LIKE \'%'.$term.'%\' ';
            }
        }
        
        $query .= ') AND LightDeleted = false ';
        
        if($userId != null){
            $query .= 'AND (Public = true OR (UserID = '.$userId.' AND Public = false))';
        }
        else{
            $query .= 'AND Public = true';
        }

        try{
            $searchQuery = $db->prepare($query);
            $searchQuery->execute();
            $searchResults = $searchQuery->fetchAll(PDO::FETCH_ASSOC);
           
            if($searchResults == null){
                $outputRows = 'No matching results. Please try with a different search.';
            }
            else{
                $outputRows = '';
                
                $searchResults = orderResults($searchResults, $searchTerms);
                
                foreach($searchResults as $row){
                    // get corresponding hex value for the colour id of the light.
                    $lightColour = $db->prepare("SELECT * FROM tblLightColour WHERE ColourID = :colourId");
                    $lightColour->bindParam(':colourId', $row['ColourID']);
                    $lightColour->execute();
                    $hexCode = $lightColour->fetch(PDO::FETCH_ASSOC);
                    
                    $lightUrl = "https://greenlight-drop-table-team-hypnotik.c9users.io/lights/".$row['LightID'];
                   
                    $bgColour = $hexCode['HexValue'];
                    
                    if($row['State']==0){
                        $bgColour = "#7E7E7E";
                    }
                    
                    $outputRows .= "  
                        <div class='panel panel-default' style='padding-left: 10px; padding-right: 10px; padding-bottom:10px'>
                            <div class='row'>
                                <div class='panel-body'>
                                    <div class='col-md-2 temp-greenlight' style='background: ".$bgColour."';>
                                    </div>
                                    <div class='col-md-10'>
                                        <b><a href='/lights/".$row['LightID']."'>".$row['LightTitle']."</a></b>
                                        <p>
                                            <small>".nl2br($row['Description'])."</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='row' style='margin-bottom:10px;' id='toggleNav'>
                                <div class='col-md-12'>
                                    <div class='input-group'>
                                        <input type='text' class='form-control' value='".$lightUrl."' readonly>
                                        <span class='input-group-btn'>
                                            <button class='btn btn-secondary' type='button'>Copy</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>";
                }
            }
        }
        catch(PDOException $e){
            $outputRows = '<p>'.$e->getCode().': There was a problem with the search terms.<br />Please check and try again.<br />'.$searchField.'</p>';
        }
    }
    else{
        $outputRows = 'Please enter search values.';
    }
    
    function orderResults($resultArray, $resultTerms){
        $orderedResults = array();
        
        foreach($resultArray as &$result){
            $titleMatch = 0.00;
            $descMatch = 0.00;
            
            $titleCount = 0;
            $descCount = 0;
            $termCount = 0;
            
            foreach($resultTerms as $term){
                if(preg_match('/\b'.$term.'\b/i', $result['LightTitle'])){
                    $titleCount += 1;
                }
                
                if(preg_match('/\b'.$term.'\b/i', $result['Description'])){
                    $descCount += 1;
                }
            }
            
            $titleMatch = ($titleCount / count($resultArray)) * 0.75;
            $descMatch = ($descCount / count($resultArray)) * 0.25;
            $match = $titleMatch + $descMatch;
            
            if($match != 0){
                $result['Match'] = $match;
            
                $orderedResults[] = $result;
            }
        }
        
        usort($orderedResults, 'cmp');
        
        return $orderedResults;
    }
    
    function cmp($a, $b){
        if ($a['Match'] == $b['Match']){
            return 0;
        }
        
        return ($a['Match'] < $b['Match']) ? 1 : -1;
    }
?> 


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Greenlight">
    <meta name="author" content=";DROP TABLE team - RMIT University">

    <!-- favicons -->
  <link rel="shortcut icon" href="/images/favicon/favicon.ico">
  <link rel="icon" sizes="16x16 32x32 64x64" href="/images/favicon/favicon.ico">
  <link rel="icon" type="image/png" sizes="196x196" href="/images/favicon/favicon-192.png">
  <link rel="icon" type="image/png" sizes="160x160" href="/images/favicon/favicon-160.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96.png">
  <link rel="icon" type="image/png" sizes="64x64" href="/images/favicon/favicon-64.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16.png">
  <link rel="apple-touch-icon" href="/images/favicon/favicon-57.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/favicon-114.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/favicon-72.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/favicon-144.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/favicon-60.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/favicon-120.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/favicon-76.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/favicon-152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/favicon-180.png">
  <meta name="msapplication-TileColor" content="#FFFFFF">
  <meta name="msapplication-TileImage" content="/images/favicon/favicon-144.png">
  <meta name="msapplication-config" content="/browserconfig.xml">

    <title>Greenlight</title>

    <!-- css -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

    <!-- js / jquery -->
    <script src="../js/jquery-2.2.4.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

</head>

<body>
    <!-- nav bar + header -->
    <?php
  
  if(!isset($_SESSION['user_name'])){ 
    include("../modules/nav.php");
  }
  else{
    include("../modules/nav-loggedin.php");
  }
  
  ?>

    <!-- content and footer -->
    <div class="container">

        <!-- misc test content -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Search
                </h1>
            </div>
            <div class="col-md-12">
                <div class="panel panel-no-border">
                    <div class="panel-body">
                        
                        <!-- search functionality needs to be written -->
                        
                        <form class="form-inline" role="search" action="/search/?searchField">
                            <div class="form-group">
                                <input type="text" name="searchField" class="form-control" placeholder="Search">
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-no-border">
                    <div class="panel-body">
                        <?php
                            echo $outputRows;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <!-- footer -->
        <?php include("../modules/footer-search.php") ?>

    </div>
    <!-- /contatiner -->
</body>

</html>
