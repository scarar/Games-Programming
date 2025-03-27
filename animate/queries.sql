-- Get all users
SELECT * FROM users;

-- Get user by ID
SELECT * FROM users WHERE id = ?;

-- Create new user
INSERT INTO users (username, email, password) VALUES (?, ?, ?);

-- Update user
UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?;

-- Delete user
DELETE FROM users WHERE id = ?;

-- Get all posts
SELECT * FROM posts;

-- Get post by ID
SELECT * FROM posts WHERE id = ?;

-- Create new post
INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?);

-- Update post
UPDATE posts SET title = ?, content = ? WHERE id = ?;

-- Delete post
DELETE FROM posts WHERE id = ?; 