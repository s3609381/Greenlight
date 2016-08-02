<div class="col-md-2">

    <div class="list-group">
        
        <button type="button" class="list-group-item active nav-header">Navigation</button>
        
        <!-- delete this session dump button before production -->
        <button type="button" class="list-group-item" onclick="window.location='/session_dump.php';">View Session</button>
        
        
        <button type="button" class="list-group-item" onclick="window.location='/dashboard';">Home</button>
        

        <!-- Create Light links collapsable so that you can choose enhanced or basic -->
        <button href="#toggleNav" class="nav-toggle list-group-item active" type="button">Create Light   <span class="glyphicon glyphicon-plus pull-right" aria-hidden="true"></span></button>
        <div id="toggleNav" style="display:none">
            <button type="button" class="list-group-item" onclick="window.location='/dashboard/create-basic-light';">Basic Light</button>
            <button type="button" class="list-group-item disabled" onclick="window.location='/dashboard/create-enhanced-light';">Enhanced Light</button>
        </div>
        
        <button type="button" class="list-group-item active" onclick="window.location='/user/<?php echo $_SESSION['user_id'] ?>';">Profile</button>
        <button type="button" class="list-group-item" onclick="window.location='/user/settings/<?php echo $_SESSION['user_id'] ?>';">Settings</button>
        
        <button type="button" class="list-group-item" onclick="window.location='/logout.php';">Log Out</button>


    </div>

</div>