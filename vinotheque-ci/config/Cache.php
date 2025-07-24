<?php
// config/Cache.php

class Cache {
    private $cacheDir;
    private $cacheTime;

    public function __construct($cacheDir = 'cache/', $cacheTime = 3600) {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
        $this->cacheTime = $cacheTime;
        
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function get($key) {
        $filename = $this->getCacheFilename($key);
        
        if (file_exists($filename) && 
            (time() - filemtime($filename)) < $this->cacheTime) {
            return unserialize(file_get_contents($filename));
        }
        
        return false;
    }

    public function set($key, $data) {
        $filename = $this->getCacheFilename($key);
        return file_put_contents($filename, serialize($data));
    }

    public function delete($key) {
        $filename = $this->getCacheFilename($key);
        if (file_exists($filename)) {
            return unlink($filename);
        }
        return false;
    }

    public function clear() {
        $files = glob($this->cacheDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }

    private function getCacheFilename($key) {
        return $this->cacheDir . md5($key) . '.cache';
    }
}
?>