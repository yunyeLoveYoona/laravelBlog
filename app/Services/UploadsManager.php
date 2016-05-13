<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UploadsManager
{

    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk(config('blog.uploads.storage'));
    }

    /**
     * Return files and directories within a folder
     *
     * @param string $folder            
     * @return array of [
     *         'folder' => 'path to current folder',
     *         'folderName' => 'name of just current folder',
     *         'breadCrumbs' => breadcrumb array of [ $path => $foldername ]
     *         'folders' => array of [ $path => $foldername] of each subfolder
     *         'files' => array of file details on each file in folder
     *         ]
     */
    public function folderInfo($folder)
    {
        $folder = $this->cleanFolder($folder);
        
        $breadcrumbs = $this->breadcrumbs($folder);
        $slice = array_slice($breadcrumbs, - 1);
        $folderName = current($slice);
        $breadcrumbs = array_slice($breadcrumbs, 0, - 1);
        
        $subfolders = [];
        foreach (array_unique($this->disk->directories($folder)) as $subfolder) {
            $subfolders["/$subfolder"] = basename($subfolder);
        }
        
        $files = [];
        
        foreach ($this->disk->files($folder) as $path) {
            $files[] = $this->fileDetails($path);
        }
        
        return compact('folder', 'folderName', 'breadcrumbs', 'subfolders', 'files');
    }

    /**
     * 鍒涘缓鏂扮洰褰�
     */
    public function createDirectory($folder)
    {
        $folder = $this->cleanFolder($folder);
        
        if ($this->disk->exists($folder)) {
            return "Folder '$folder' already exists.";
        }
        
        return $this->disk->makeDirectory($folder);
    }

    /**
     * 鍒犻櫎鐩綍
     */
    public function deleteDirectory($folder)
    {
        $folder = $this->cleanFolder($folder);
        
        $filesFolders = array_merge($this->disk->directories($folder), $this->disk->files($folder));
        if (! empty($filesFolders)) {
            return "Directory must be empty to delete it.";
        }
        
        return $this->disk->deleteDirectory($folder);
    }

    /**
     * 鍒犻櫎鏂囦欢
     */
    public function deleteFile($path)
    {
        $path = $this->cleanFolder($path);
        
        if (! $this->disk->exists($path)) {
            return "File does not exist.";
        }
        
        return $this->disk->delete($path);
    }

    /**
     * 淇濆瓨鏂囦欢
     */
    public function saveFile($path, $content)
    {
        $path = urlencode($path);
        $path = str_replace("%2F", "/", $path);
        echo ($path);
        $path = str_replace("%", "[]", $path);
        echo ($path);
        if ($this->disk->exists($path)) {
            return "File already exists.";
        }
        return $this->disk->put($path, $content);
    }

    /**
     * Sanitize the folder name
     */
    protected function cleanFolder($folder)
    {
        return '/' . trim(str_replace('..', '', $folder), '/');
    }

    /**
     * 鏉╂柨娲栬ぐ鎾冲閻╊喖缍嶇捄顖氱窞
     */
    protected function breadcrumbs($folder)
    {
        $folder = trim($folder, '/');
        $crumbs = [
            '/' => 'root'
        ];
        
        if (empty($folder)) {
            return $crumbs;
        }
        
        $folders = explode('/', $folder);
        $build = '';
        foreach ($folders as $folder) {
            $build .= '/' . $folder;
            $crumbs[$build] = $folder;
        }
        
        return $crumbs;
    }

    /**
     * 鏉╂柨娲栭弬鍥︽鐠囷妇绮忔穱鈩冧紖閺佹壆绮�
     */
    protected function fileDetails($path)
    {
        $path = '/' . ltrim($path, '/');
        $name = str_replace("[]", "%", $path);
        $name = urldecode($name);
        return [
            'name' => basename($name),
            'fullPath' => $path,
            'webPath' => $this->fileWebpath($path),
            'mimeType' => $this->fileMimeType($path),
            'size' => $this->fileSize($path),
            'modified' => $this->fileModified($path)
        ];
    }

    /**
     * 鏉╂柨娲栭弬鍥︽鐎瑰本鏆ｉ惃鍓媏b鐠侯垰绶�
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('blog.uploads.webpath'), '/') . '/' . ltrim($path, '/');
        return url($path);
    }

    /**
     * 鏉╂柨娲栭弬鍥︽MIME缁鐎�
     */
    public function fileMimeType($path)
    {
        $finfo = finfo_open(FILEINFO_MIME);
        $basepath = dirname(__FILE__);
        $basepath = substr($basepath, 0, strpos($basepath, "blog") + 4);
        $basepath = str_replace("\\", "/", $basepath);
        $basepath = $basepath . "/public/uploads";
        $path = $basepath . $path;
        $mimetype = finfo_file($finfo, $path);
        finfo_close($finfo);
        return $mimetype;
    }

    /**
     * 鏉╂柨娲栭弬鍥︽婢堆冪毈
     */
    public function fileSize($path)
    {
        return $this->disk->size($path);
    }

    /**
     * 鏉╂柨娲栭張锟介崥搴濇叏閺�瑙勬闂傦拷
     */
    public function fileModified($path)
    {
        return Carbon::createFromTimestamp($this->disk->lastModified($path));
    }
}