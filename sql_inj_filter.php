<?php
function filter_sql($input)
{
    $reg[0]="/delete/";
    $reg[1]="/OR/";
    $reg[2]="/insert/";
    $reg[3]="/update/";

    return(preg_replace($reg, "", $input)); //a regben lévő ha bentvan az inputban, akkor kicseréli üresre
}