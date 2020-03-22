<?php 

namespace Neko\Framework\Util;

class File {


    public $name;

    public $extension;

    protected $tmp;

    protected $size;

    protected $error;

    protected $mimeType;

    public function getContent($file)
    {
        $data=(string) file_get_contents($file);
        return $data;
    }

    public function isExist($file)
    {
        if(file_exists($file))
        {
            return true;
        }else{
            return false;
        }
    }
    

    public function getMimeType($file)
    {
        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        //$ext = strtolower(array_pop(explode('.',$file)));
        $ext = substr(substr($file, strrpos($file, '.', -1), strlen($file)),1);
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $file);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }


    public function preview($file)
    {
        global $app;

        $lifetime = 31556926; // One year in seconds

        /**
        * Prepare some header variables
        */
        $file_time = filemtime($file); // Get the last modified time for the file (Unix timestamp)
        $header_content_type = self::getMimeType($file);
        $header_content_length = filesize($file);
        $header_etag = md5($file_time . $file);
        $header_last_modified = gmdate('r', $file_time);
        $header_expires = gmdate('r', $file_time + $lifetime);

        /**
        * Is the resource cached?
        */
        $h1 = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $header_last_modified;
        $h2 = isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $header_etag;

        if ($h1 || $h2) {
           //fixme $app->response->setHeader('Content-Disposition','inline; filename="' . $file . '"');
            $app->response->setHeader('Last-Modified',$header_last_modified);
            $app->response->setHeader('Cache-Control','must-revalidate');
            $app->response->setHeader('Expires',$header_expires);
            $app->response->setHeader('Pragma','public');
            $app->response->setHeader('Etag',$header_etag);
            return $app->response->setStatus('304');
        }

        //fixme $app->response->setHeader('Content-Disposition','inline; filename="' . $file . '"');
        $app->response->setHeader('Last-Modified',$header_last_modified);
        $app->response->setHeader('Cache-Control','must-revalidate');
        $app->response->setHeader('Expires',$header_expires);
        $app->response->setHeader('Pragma','public');
        $app->response->setHeader('Etag',$header_etag);
        $app->response->setHeader('Content-Type',$header_content_type);
        $app->response->setHeader('Content-Length',$header_content_length);
        return self::getContent($file);//Response::make(file_get_contents($path), 200, $headers);
    }

}