<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . 'third_party/ImageKit/ImageKit.php');

class Imagekit_lib
{
    private $imageKit;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('imagekit');

        $privateKey = $this->CI->config->item('imagekit_private_key');
        $publicKey = $this->CI->config->item('imagekit_public_key');
        $urlEndpoint = $this->CI->config->item('imagekit_url_endpoint');

        $this->imageKit = new ImageKit($privateKey, $publicKey, $urlEndpoint);
    }

    public function upload($file)
    {
        $options = [
            'folder' => '/products/', // Replace with your desired folder name
        ];

        return $this->imageKit->upload(array('file' => $file), $options);
    }
}
