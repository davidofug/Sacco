<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David-FortisArt
 * Date: 3/31/13
 * Time: 11:47 PM
 * To change this template use File | Settings | File Templates.
 */
$to = "desaint.david@gmail.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: developer@fortisart.net" . "\r\n";
mail($to,$subject,$txt,$headers);
?>