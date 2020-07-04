<?php
    include "script.php";
    $time1 = $DB->date();
    $time2 = "2020-06-04 12:12:59";
    $timestamp = strtotime($time1);
    $timestamp1 = strtotime($time2);
    $diff=$timestamp1-$timestamp;
    echo "$time1 $timestamp<br/>$time2 $timestamp1<br/>$diff";
?>