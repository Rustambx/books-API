<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait MediaTrait
{
    private $audioMimeType = 'audio/mpeg';

    private $ebookMimeType = [
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/pdf'
    ];

    private $imageType = [
        "image/jpeg",
        "image/png"
    ];

    public function checkAudioMimeType ($file)
    {
        $mimeType = $file->getMimeType();
        if ($mimeType == $this->audioMimeType) {
            return true;
        } else {
            return false;
        }
    }

    public function checkEbookMimeType ($file)
    {
        $mimeType = $file->getMimeType();
        if (in_array($mimeType, $this->ebookMimeType)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkImageMimeType ($image)
    {
        $mimeType = $image->getMimeType();
        if (in_array($mimeType, $this->imageType)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param UploadedFile $file
     * @return \StdClass
     */
    protected function prepareFile(UploadedFile $file)
    {
        $preparedFile = new \StdClass;

        $parts = explode('.', $file->getClientOriginalName());
        $preparedFile->hash = md5($parts[0]);
        $preparedFile->name = md5($parts[0]).'.'.last($parts);

        $preparedFile->path = substr(md5(microtime()), mt_rand(0, 30), 5);

        return $preparedFile;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadImage(UploadedFile $file)
    {
        $upload = $this->prepareFile($file);

        $file->storeAs('public/upload/images/'.$upload->path, $upload->name);

        return $upload->path.'/'.$upload->name;
    }


    public function saveFiles (UploadedFile $file)
    {
        $upload = $this->prepareFile($file);

        $file->storeAs('public/upload/files/'.$upload->path, $upload->name);

        // TODO save file to table

        return $upload->path.'/'.$upload->name;
    }

    public function saveAudios (UploadedFile $file)
    {
        $upload = $this->prepareFile($file);

        $file->storeAs('public/upload/files/'.$upload->path, $upload->name);

        // TODO save file to table

        return $upload->path.'/'.$upload->name;
    }
}
