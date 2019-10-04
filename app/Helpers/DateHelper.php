<?php

function shortDate($date)
{
    return ($date != null) ? date('m/d/Y', strtotime($date)) : '-';
}

function longDate($date)
{
    return ($date != null) ? date('F d, Y', strtotime($date)) : '-';
}

function laravelDate($date)
{
    return ($date != null) ? date('Y-m-d', strtotime($date)) : '-';
}