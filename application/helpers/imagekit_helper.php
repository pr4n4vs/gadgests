<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once APPPATH . 'helpers/imagekit/ImageKit.php';
// require_once APPPATH . 'helpers/imagekit/Utils/Transformation.php';
// require_once APPPATH . 'helpers/imagekit/Configuration/Configuration.php';
// require_once APPPATH . 'helpers/imagekit/GuzzleHttp/Client.php';
require_once APPPATH .'../vendor/autoload.php';
function get_imagekit_instance()
{
    $ci = &get_instance();
    $privateKey = 'private_L7CEn8Sr6/79HMWWYGmr4BL3GfI=';
    $publicKey = 'public_PK0IdnZqslL0W8TtdI13tICk6vw=';
    $imagekitId = 'https://ik.imagekit.io/k2uegtqj2';

    return new ImageKit\ImageKit($privateKey, $publicKey, $imagekitId);
}
