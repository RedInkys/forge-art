<?php
function debug($var,$mode = 1)
{
$trace = debug_backtrace();//Il recupere tout 
$trace = array_shift($trace);//array_shift permet de recuperer le premiere element

echo "<strong>Debug demande dans le fichier ".$trace['file']." a la ligne ".$trace['line']. "</strong>";

if($mode == 1)
{
    echo "<pre>";var_dump($var);echo "</pre>";
}else{
    echo "<pre>";print_r($var);echo "</pre>";
}
}


function internauteConnecte(){
    if(!isset($_SESSION['membre']))
    {
        return false;
    }else{
        return true;
    }
}

?>