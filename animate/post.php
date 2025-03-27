<?php
class Post {
    private $file_path;
    private $posts;

    public $id;
    public $title;
    public $content;
    public $user_id;
    public $is_private;

    public function __construct() {
        // Create a storage directory in the project root
        $storage_dir = __DIR__ . '/storage';
        if (!file_exists($storage_dir)) {
            mkdir($storage_dir, 0777, true);
        }
        $this->file_path = $storage_dir . '/posts.json';
        $this->loadPosts();
    }

    private function loadPosts() {
        if (!file_exists($this->file_path)) {
            // Create the file with empty posts array if it doesn't exist
            $this->posts = [];
            $this->savePosts();
        } else {
            $json = file_get_contents($this->file_path);
            $data = json_decode($json, true);
            if ($data === null) {
                // If JSON is invalid, initialize with empty array
                $this->posts = [];
                $this->savePosts();
            } else {
                $this->posts = $data['posts'] ?? [];
            }
        }
    }

    private function savePosts() {
        $data = ['posts' => $this->posts];
        $json = json_encode($data, JSON_PRETTY_PRINT);
        if ($json === false) {
            error_log("Error encoding posts to JSON: " . json_last_error_msg());
            return false;
        }
        
        // Log the file path and permissions
        error_log("Attempting to save posts to: " . $this->file_path);
        error_log("File exists: " . (file_exists($this->file_path) ? 'Yes' : 'No'));
        if (file_exists($this->file_path)) {
            error_log("File permissions: " . substr(sprintf('%o', fileperms($this->file_path)), -4));
            error_log("File owner: " . posix_getpwuid(fileowner($this->file_path))['name']);
            error_log("File group: " . posix_getgrgid(filegroup($this->file_path))['name']);
        }
        
        // Try to create directory if it doesn't exist
        $dir = dirname($this->file_path);
        if (!file_exists($dir)) {
            error_log("Creating directory: " . $dir);
            if (!mkdir($dir, 0777, true)) {
                error_log("Failed to create directory: " . $dir);
                return false;
            }
        }
        
        // Check directory permissions
        error_log("Directory permissions: " . substr(sprintf('%o', fileperms($dir)), -4));
        error_log("Directory owner: " . posix_getpwuid(fileowner($dir))['name']);
        error_log("Directory group: " . posix_getgrgid(filegroup($dir))['name']);
        
        if (file_put_contents($this->file_path, $json) === false) {
            error_log("Error saving posts to file. Error: " . error_get_last()['message']);
            return false;
        }
        return true;
    }

    public function read($show_private = false) {
        // Reload posts from file to ensure we have latest data
        $this->loadPosts();
        
        // Filter posts based on visibility
        if (!$show_private) {
            return array_filter($this->posts, function($post) {
                return !isset($post['is_private']) || !$post['is_private'];
            });
        }
        return $this->posts;
    }

    public function readOne($id, $show_private = false) {
        $this->loadPosts(); // Reload to ensure latest data
        foreach ($this->posts as $post) {
            if ($post['id'] == $id) {
                // Check if post is private and if we should show it
                if (isset($post['is_private']) && $post['is_private'] && !$show_private) {
                    return null;
                }
                return $post;
            }
        }
        return null;
    }

    public function create() {
        $newPost = [
            'id' => time(), // Using timestamp as ID
            'title' => htmlspecialchars(strip_tags($this->title)),
            'content' => htmlspecialchars(strip_tags($this->content)),
            'user_id' => $this->user_id,
            'is_private' => isset($this->is_private) ? (bool)$this->is_private : false,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->posts[] = $newPost;
        return $this->savePosts();
    }

    public function update() {
        foreach ($this->posts as &$post) {
            if ($post['id'] == $this->id) {
                $post['title'] = htmlspecialchars(strip_tags($this->title));
                $post['content'] = htmlspecialchars(strip_tags($this->content));
                if (isset($this->is_private)) {
                    $post['is_private'] = (bool)$this->is_private;
                }
                $post['updated_at'] = date('Y-m-d H:i:s');
                return $this->savePosts();
            }
        }
        return false;
    }

    public function delete() {
        foreach ($this->posts as $key => $post) {
            if ($post['id'] == $this->id) {
                unset($this->posts[$key]);
                $this->posts = array_values($this->posts); // Reindex array
                return $this->savePosts();
            }
        }
        return false;
    }
}
?> 