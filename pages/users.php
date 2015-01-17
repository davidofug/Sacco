<form action="" method="post" >
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David-FortisArt
 * Date: 4/12/13
 * Time: 5:48 PM
 * To change this template use File | Settings | File Templates.
 *
 */
ini_set('date.timezone','Africa/Nairobi');
$driver = new driver;
if($_SESSION['lvl'] != 2){
    echo '<p class="error">You are not authorized to view this resource!</p>';
}else{
$query = "SELECT * FROM users";
$results = $driver->perform_request($query) or  /* Here we put the code for error or activity logs */ die('<p class="error">Users can not be loaded<br/>.'.mysql_error().'</p>');
if($driver->numRows($results)<=0){
?>
<div class="notice">
    <div class="innerpost">
        <div class="heading"><h3>System Alert</h3></div>
        <div class="content">
            <p>The system can not find registered to display. You might need <a href="?users.php&register" title="Register users">Create new users</a></p>
        </div>
    </div>
</div>
<?php
}else{
?>
 <div class="filter"><label>Search</label><input type="text" id="searchterm" class="textbox" />
 <label>Filter:</label><select id="status" name="status">
         <option value="status">Status</option>
         <option value="0">Disabled</option>
         <option value="1">Enabled</option>
         <option value="2">Suspended</option>
 </select>
 <select id="acl" name="acl">
         <option value="acl">Select ACL</option>
         <option value="1">Accountant</option>
         <option value="2">Administrator</option>
         <option value="3">Client</option>
     </select>
     <select id="onoroff" name="onoroff">
         <option value="acl">Status</option>
         <option value="0">Online</option>
         <option value="1">Offline</option>
     </select>
<input type="submit" name="go" id="go" value="Go" />
 </div>
    <table align="center" width="90%" border="0" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
             <th><a href="all#">CHECK ALL/NONE</a></th> <th>USER ID</th><th>REGISTERED ON</th><th>REAL NAME</th><th>USERNAME</th><th>Access Control Level</th><th>USER STATUS</th><th>ACTIVITY STATUS</th><th>ACTIONS</th>
            </tr>
        </thead>
<?php
}
while($row = $driver->load_data($results,MYSQL_ASSOC)):
?>
    <tbody>
    <tr>
        <td><input type="checkbox" name="userid[]" value="<?php echo $row['id'];?>" /></td><td><?php echo $row['id']; ?></td><td><?php echo $row['date']; ?></td><td><?php echo $row['name']; ?></td><td><?php echo $row['uname']; ?></td>
       <td> <?php
            switch($row['model']):
            CASE 1:
            echo "Accountant";
            break;
            CASE 2:
            echo "Administrator";
            break;
            CASE 3:
            echo "Client";
            break;
            endswitch;
        ?>
        </td>
        <td><?php
            switch($row['status']):
                CASE 0:
                echo 'Disabled';
                break;
                CASE 1:
                echo 'enabled';
                break;
                CASE 2:
                echo 'Suspended';
                break;
                default:
                echo 'Disable';
                break;
            endswitch;
            ?></td>
        <td><?php
                switch($row['onoroff']):
                    CASE 0:
                    echo 'Online';
                    break;
                    CASE 1:
                    echo 'Offline';
                    break;
                    DEFAULT:
                    echo 'Offline';
                    BREAK;
                endswitch;
           ?></td>
        <td>
            <a href="?page=users.php&edit=1&user=<?php echo $row['id']; ?>">Edit</a>
            <a href="?page=users.php&del=1&user=<?php echo $row['id']; ?>">Delete</a>
            <?php echo ($row['status']==0 or $row['status']==2)?
            '<a href="?page=users.php&state=1&user='.$row['id'].' title="Enable">Enable</a>':'<a href="?page=users.php&state=2&user='.$row['id'].'" title="Suspend" >Suspend</a>';
            ?>
        </td>
    </tr>
    </tbody>
<?php
endwhile;
}
?>
</table>
<p><a href="all#">CHECK ALL/NONE</a></p>
<div class="element">
    <label for="action">With Selected:</label>
    <select name="action" id="action">
        <option value="none">Select action</option>
        <option value="delete">Delete</option>
        <option value="1">Enable</option>
        <option value="2">Suspend</option>
    </select>
    <input type="submit" value="Go" name="performaction" />
</div>
</form>