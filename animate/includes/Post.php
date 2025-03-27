<?php
class Post {
    private $posts_dir;
    private $file_extension = '.json';
    private $error_log;

    public function __construct() {
        $this->posts_dir = __DIR__ . '/../data/posts/';
        $this->error_log = __DIR__ . '/../data/error.log';
        
        if (!file_exists($this->posts_dir)) {
            if (!mkdir($this->posts_dir, 0775, true)) {
                $this->logError("Failed to create posts directory: " . $this->posts_dir);
                throw new Exception("Failed to create posts directory");
            }
        }
        
        if (!is_writable($this->posts_dir)) {
            $this->logError("Posts directory is not writable: " . $this->posts_dir);
            throw new Exception("Posts directory is not writable");
        }
    }

    private function logError($message) {
        $timestamp = date('Y-m-d H:i:s');
        $log_message = "[$timestamp] $message\n";
        error_log($log_message, 3, $this->error_log);
    }

    private function generateId() {
        return uniqid() . '_' . time();
    }

    private function sanitizeFilename($title) {
        return preg_replace('/[^a-zA-Z0-9-]/', '_', strtolower($title));
    }

    private function extractYoutubeId($url) {
        // Match various YouTube URL formats
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|music\.youtube\.com\/watch\?v=)([^"&?\/\s]{11})/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function processContent($content) {
        // Find YouTube URLs in the content
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|music\.youtube\.com\/watch\?v=)([^"&?\/\s]{11})/';
        $content = preg_replace_callback($pattern, function($matches) {
            $videoId = $matches[1];
            return '<div class="youtube-container">
                <iframe src="https://www.youtube.com/embed/' . $videoId . '" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>
            </div>';
        }, $content);
        
        return $content;
    }

    public function create($title, $content, $is_private = 0, $youtube_id = null) {
        try {
            $id = $this->generateId();
            $filename = $this->sanitizeFilename($title) . '_' . $id . $this->file_extension;
            $filepath = $this->posts_dir . $filename;

            // Process content to handle YouTube embeds
            $processed_content = $this->processContent($content);
            
            // If no youtube_id provided, try to extract it from the first video
            if (!$youtube_id) {
                $youtube_id = $this->extractYoutubeId($content);
            }

            $post_data = [
                'id' => $id,
                'title' => $title,
                'content' => $processed_content,
                'is_private' => (bool)$is_private,
                'youtube_id' => $youtube_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $json_data = json_encode($post_data, JSON_PRETTY_PRINT);
            if ($json_data === false) {
                $this->logError("Failed to encode post data to JSON");
                throw new Exception("Failed to encode post data");
            }

            if (file_put_contents($filepath, $json_data) === false) {
                $this->logError("Failed to write post file: " . $filepath);
                throw new Exception("Failed to write post file");
            }

            return true;
        } catch (Exception $e) {
            $this->logError("Error creating post: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAll($include_private = false) {
        $posts = [];
        $files = glob($this->posts_dir . '*' . $this->file_extension);

        foreach ($files as $file) {
            $post_data = json_decode(file_get_contents($file), true);
            if ($include_private || !$post_data['is_private']) {
                $posts[] = $post_data;
            }
        }

        // Sort by creation date, newest first
        usort($posts, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $posts;
    }

    public function getById($id) {
        $files = glob($this->posts_dir . '*' . $this->file_extension);
        
        foreach ($files as $file) {
            $post_data = json_decode(file_get_contents($file), true);
            if ($post_data['id'] === $id) {
                return $post_data;
            }
        }

        return null;
    }

    public function update($id, $title, $content, $is_private = 0, $youtube_id = null) {
        $files = glob($this->posts_dir . '*' . $this->file_extension);
        
        foreach ($files as $file) {
            $post_data = json_decode(file_get_contents($file), true);
            if ($post_data['id'] === $id) {
                // Process content to handle YouTube embeds
                $processed_content = $this->processContent($content);
                
                // If no youtube_id provided, try to extract it from the first video
                if (!$youtube_id) {
                    $youtube_id = $this->extractYoutubeId($content);
                }

                $post_data['title'] = $title;
                $post_data['content'] = $processed_content;
                $post_data['is_private'] = (bool)$is_private;
                $post_data['youtube_id'] = $youtube_id;
                $post_data['updated_at'] = date('Y-m-d H:i:s');
                
                return file_put_contents($file, json_encode($post_data, JSON_PRETTY_PRINT));
            }
        }

        return false;
    }

    public function delete($id) {
        $files = glob($this->posts_dir . '*' . $this->file_extension);
        
        foreach ($files as $file) {
            $post_data = json_decode(file_get_contents($file), true);
            if ($post_data['id'] === $id) {
                return unlink($file);
            }
        }

        return false;
    }

    public function toggleVisibility($id) {
        $files = glob($this->posts_dir . '*' . $this->file_extension);
        
        foreach ($files as $file) {
            $post_data = json_decode(file_get_contents($file), true);
            if ($post_data['id'] === $id) {
                $post_data['is_private'] = !$post_data['is_private'];
                $post_data['updated_at'] = date('Y-m-d H:i:s');
                
                return file_put_contents($file, json_encode($post_data, JSON_PRETTY_PRINT));
            }
        }

        return false;
    }
} 