<?php
use Sabre\DAV;
use Sabre\DAV\Auth;
use Sabre\HTTP\URLUtil;

class myDavDirectory implements DAV\ICollection, DAV\IQuota
{

    private $path;

    function __construct($myPath)
    {

        $this->path = $myPath;
        $this->fileObj = new datei();
        $this->projectObj = new project();

    }


    /*
     * List all the child elements of a directory
     */
    function getChildren()
    {
        $children = array();
        // Loop through the directory, and create objects for each node
        foreach (scandir($this->path) as $node) {
            // Ignoring files staring with .
            if ($node[0] === '.') {
                continue;
            }
            if (!strstr($node, ".decrypt")) {
                //if its a directory check if the user belongs to the project
                if (is_dir($this->path . "/" . $node)) {
                    if (chkproject($_SESSION["userid"], $node)) {
                        $children[] = $this->getChild($node);
                    }
                } else {
                    $children[] = $this->getChild($node);
                }
            }
        }
        return $children;
    }

    /*
     * Get details about a child
     */
    function getChild($name)
    {
        $path = $this->path . '/' . $name;

        // We have to throw a NotFound exception if the file didn't exist
        if (!file_exists($path)) {
            //split up the display name, to infer the ID which is the real folder name
            $projectId = explode("-", $name);
            $path = $this->path . "/" . $projectId[1];
            if (!file_exists($path)) {
                throw new DAV\Exception\NotFound('The file with name: ' . $projectId[1] . ' could not be found');
            }
        }
        if (is_dir($path)) {
            return new myDavDirectory($path);
        } else {
            return new myDavFile($path);
        }

    }

    /**
     * Checks if a child exists.
     *
     * @param string $name
     * @return bool
     */
    function childExists($name)
    {

        $path = $this->path . "/" . $name;
        // We have to throw a NotFound exception if the file didn't exist
        if (!file_exists($path)) {
            //split up the display name, to infer the ID which is the real folder name
            if (strstr($name, "-")) {
                $projectId = explode("-", $name);
                $path = $this->path . "/" . $projectId[1];
                return file_exists($path);
            } else {
                return false;
            }

        } else {
            return false;
        }


    }

    function getName()
    {
        $projectId = basename($this->path);
        $folderName = $this->projectObj->getProject($projectId);

        if ($projectId > 0) {
            return $folderName["name"] . "-" . $projectId;
        } else {
            return $projectId;
        }
    }

    /**
     * Returns available diskspace information
     *
     * @return array
     */
    function getQuotaInfo()
    {
        $absolute = realpath($this->path);
        return [
            disk_total_space($absolute) - disk_free_space($absolute),
            disk_free_space($absolute)
        ];

    }

    /**
     * Deleted the current node
     *
     * @return void
     */
    function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * Renames the node
     *
     * @param string $name The new name
     * @return void
     */
    function setName($name)
    {

        list($parentPath,) = URLUtil::splitPath($this->path);
        list(, $newName) = URLUtil::splitPath($name);

        $newPath = $parentPath . '/' . $newName;
        rename($this->path, $newPath);

        $this->path = $newPath;

    }


    function createFile($name, $data = null)
    {
        //create path for file and infer project id
        $newPath = $this->path . '/' . $name;
        $projectId = basename($this->path);

        //create the file
        clearstatcache(true, $newPath);
        file_put_contents($newPath, $data);

        $fileType = mime_content_type($newPath);

        //add the file to the collabtive file manager
        $this->fileObj->add_file($name, "", $projectId, 0, $newPath, $fileType);
        clearstatcache(true, $newPath);

    }

    /**
     * Creates a new subdirectory
     *
     * @param string $name
     * @return void
     */
    function createDirectory($name)
    {
        $newPath = $this->path . '/' . $name;
        if (!file_exists($newPath)) {
            //split up the display name, to infer the ID which is the real folder name
            $pathName = explode("-", $name);
            //  $newPath = $this->path . "/" . $pathName[1];
        }
        clearstatcache(true, $newPath);
        mkdir($newPath);
        clearstatcache(true, $newPath);

    }

    /**
     * Returns the last modification time, as a unix timestamp
     *
     * @return int
     */
    function getLastModified()
    {
        return filemtime($this->path);
    }

}
