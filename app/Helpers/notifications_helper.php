<?php

use App\Models\Notification;

function notification()
{
    $notification = new Notification();
    $data =  $notification->findAll();
    return $data;
}

function countNotification()
{
    $notification = new Notification();
    $data =  $notification->findAll();
    return  count($data);
}