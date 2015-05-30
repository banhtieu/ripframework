<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/20/2015
 * Time: 1:06 AM
 */

namespace Core\Service;


class UploadedFile {

    /**
     * @var string file name
     */
    public $name;

    /**
     * @var int file size in bytes
     */
    public $size;


    /**
     * @var the path to temporary file
     */
    public $tempFile;

    /**
     * @var int error code
     */
    public $error;

    /**
     * @var string the content type
     */
    public $type;

    /**
     * @param $name string of the uploaded file
     */
    public function __construct($name) {
        if (isset($_FILES[$name])) {
            $file = $_FILES[$name];

            $this->name = $file["name"];
            $this->size = $file["size"];
            $this->tempFile = $file["tmp_name"];
            $this->error = $file["error"];
        }
    }


    /**
     * Save this current file
     * @param $destination string the target path
     * @return boolean true if success
     */
    public function save($destination){
        return $this->error == 0 && move_uploaded_file($this->tempFile, $destination);
    }

}