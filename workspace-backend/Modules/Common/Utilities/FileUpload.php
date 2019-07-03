<?php
/**
 * Created by PhpStorm.
 * User: reshman
 * Date: 28/6/18
 * Time: 7:46 PM
 */

namespace Modules\Common\Utilities;


use Illuminate\Support\Facades\Storage;

class FileUpload
{
    public $path;
    public $name;
    public $file;

    public function __construct()
    {
        $this->path = "";
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $name
     */
    public function setFile($name)
    {
        $this->file = $name;
    }

    public function s3Upload()
    {
        $originalFileName = $this->getFile()->getClientOriginalName();
        $filename   = $this->getPath(). '/' .$originalFileName;
        $s3fileName = Storage::disk('s3')->put($filename, file_get_contents($this->getFile()), 'public');
        return $s3fileName;
    }

    public function s3UploadRename($rename = NULL)
    {
        $originalFileName = ($rename) ? $rename :  $this->getFile()->getClientOriginalName();
        $filename   = $this->getPath(). '/' .$originalFileName;
        $s3fileName = Storage::disk('s3')->put($filename, file_get_contents($this->getFile()), 'public');
        return $s3fileName;
    }

    public function dummyFile($newFileName)
    {
        $filename   = $this->getPath(). '/' .$originalFileName. '/' .$newFileName;
        $s3fileName = Storage::disk('s3')->put($filename, $this->getFile(), 'public');
    }

    /**
     *
     * @param array $files
     * ['12817985105b5ffccdac6aa/task/3127681445b67f9558f746/tree query']
     */
    public function deleteFiles(array $files)
    {
        $s3fileName = Storage::disk('s3')->delete($files);
    }

    public function deleteDir($filePath)
    {
        $s3fileName = Storage::disk('s3')->deleteDirectory($filePath);
    }

    public function copy($source, $destination)
    {
        $s3fileName = Storage::disk('s3')->copy($source, $destination);
    }

    public function rename($source, $destination)
    {
        $s3fileName = Storage::disk('s3')->move($source, $destination);
    }

    public function getAllFiles($path) {
        $allFiles = Storage::disk('s3')->allFiles($path);
        return $allFiles;
    }


}