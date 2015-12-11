<?php

echo $d = date('Y/m/d');
echo '<br>';
echo $dateperemp = preg_replace('[\/]','-',$d);

echo '<br>';
echo $d = date('Y-m-d');
echo '<br>';
echo $dateperemp = preg_replace('[-]','/',$d);

?>