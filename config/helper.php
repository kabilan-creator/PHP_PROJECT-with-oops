<?php


function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // Get only up to 'new project'
    $projectFolder = '/new project'; // Change this if your project folder is different

    return $protocol . '://' . $host . $projectFolder;
}


function url($endPoint){
   return getBaseUrl().$endPoint;
}