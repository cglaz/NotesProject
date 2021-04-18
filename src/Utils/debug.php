<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors','1');

function dump($date)
{
    echo '<br><div 
    style="
        margin-left: 5px;
        display: inline-block;
        padding: 0 10px;
        border: 1px dashed gray;
        background: lightgray;
    ">
    <pre>';
    print_r($date);
    echo '</pre>
    </div>
    <br><br>';
}

