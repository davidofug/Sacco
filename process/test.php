<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David-FortisArt
 * Date: 3/17/13
 * Time: 3:28 PM
 * To change this template use File | Settings | File Templates.
 */

$Present_Value = 100;
$Int_Rate = 2.3;
$Num_Periods = 4;
$Future_Value = round($Present_Value * pow(1 + ($Int_Rate/100),$Num_Periods), 0);
echo "$Present_Value @ $Int_Rate % over $Num_Periods periods becomes $Future_Value";
?>