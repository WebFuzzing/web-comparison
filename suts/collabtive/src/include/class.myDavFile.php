<?php
use Sabre\DAV;
use Sabre\DAV\Auth;
use Sabre\HTTP\URLUtil;

class myDavFile implements DAV\IFile
{

    private $myFilePath;

    function __construct($myPath)
    {

        $this->systemSettings = new settings();
        $this->myFilePath = $myPath;
        $this->fileObj = new datei();
    }
    /**
     * Updates the data
     *
     * @param resource $data
     * @return void
     */
    function put($data) {

        file_put_contents($this->myFilePath, $data);
        clearstatcache(true, $this->myFilePath);

    }

    /*
    * Get the content of a file
    */
    function get()
    {
        //get system settings
        $settings = $this->systemSettings->getSettings();
        //Try to decrypt the file
        $plaintext = $this->fileObj->decryptFile($this->myFilePath, $settings["filePass"]);

        //no plaintext means file was not encrypted or not decrypted. however deliver to unmodified file
        if (!$plaintext) {
            $filetext = file_get_contents($this->myFilePath);
        } //file was decrypted. create a decrypted file int he FS
        else {
            file_put_contents($this->myFilePath . ".decrypt", $plaintext);
            $filetext = file_get_contents($this->myFilePath . ".decrypt");
        }

        echo $filetext;

        //remove the temporary decrpyted file
        return  true;
    }

    /*
    * Get the filesize of a file
    */
    function getSize()
    {
        $size = filesize($this->myFilePath . ".decrypt");
        if ($size < 1) {
           $size = filesize($this->myFilePath);
        }
        return $size;
    }

    /*
    * Get hash of a file
    */
    function getETag()
    {
        return '"' . sha1(
            fileinode($this->myFilePath) .
            filesize($this->myFilePath) .
            filemtime($this->myFilePath)
        ) . '"';

    }


    /**
     * Delete the current file
     *
     * @return void
     */
    function delete()
    {

        $file = $this->fileObj->getFileByName(basename($this->myFilePath));
        $this->fileObj->loeschen($file["ID"]);
    }

    /**
     * Returns the mime-type for a file
     *
     * If null is returned, we'll assume application/octet-stream
     *
     * @return mixed
     */
    function getContentType()
    {

        return mime_content_type($this->myFilePath . ".decrypt");

    }

    /*
    * Get the name of a single file
    */
    function getName()
    {
        list(, $name) = URLUtil::splitPath($this->myFilePath);
        return $name;
        //return basename($this->myFilePath);
    }

    /*
     * Set the name of a file
     */
    function setName($name)
    {
        list($parentPath,) = URLUtil::splitPath($this->myFilePath);
        list(, $newName) = URLUtil::splitPath($name);

        $newPath = $parentPath . '/' . $newName;
        rename($this->myFilePath, $newPath);

        $this->myFilePath = $newPath;
    }

    /**
     * Returns the last modification time, as a unix timestamp
     *
     * @return int
     */
    function getLastModified()
    {

        return filemtime($this->myFilePath);

    }
}