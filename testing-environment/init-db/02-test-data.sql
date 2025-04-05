-- Insert test data for the Animate CMS
USE animate;

-- Insert admin user (username: admin, password: admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$YYxnpFMgUVBiDSn5COHKWO1bHIfnVJ.xvBjOWIkjsAB5f0gRrIkuy');

-- Insert categories
INSERT INTO categories (name, description) VALUES
('Web Development', 'Articles about web development technologies and techniques'),
('Design', 'Articles about design principles and tools'),
('Animation', 'Articles about animation techniques and tools');

-- Insert tags
INSERT INTO tags (name) VALUES
('PHP'),
('JavaScript'),
('CSS'),
('HTML'),
('MySQL'),
('UI/UX'),
('Responsive'),
('Performance');

-- Insert test users
INSERT INTO users (username, email, password) VALUES
('testuser1', 'testuser1@example.com', '$2y$10$YYxnpFMgUVBiDSn5COHKWO1bHIfnVJ.xvBjOWIkjsAB5f0gRrIkuy'),
('testuser2', 'testuser2@example.com', '$2y$10$YYxnpFMgUVBiDSn5COHKWO1bHIfnVJ.xvBjOWIkjsAB5f0gRrIkuy');

-- Insert test posts
INSERT INTO posts (title, content, author_id, created_at, status) VALUES
('Test Post from Web Server', 'This is a test post to verify that the web server is working correctly. The animate website is now fully functional!', 1, '2025-04-03 14:28:30', 'published'),
('Getting Started with Animation', 'Animation is a powerful tool for creating engaging user experiences. In this post, we will explore the basics of animation and how to implement it in your web projects.', 1, '2025-04-02 10:15:00', 'published'),
('Responsive Design Techniques', 'Responsive design is essential for creating websites that work well on all devices. In this post, we will explore some techniques for creating responsive designs.', 1, '2025-04-01 09:30:00', 'published'),
('Draft Post Example', 'This is a draft post that is not yet published.', 1, '2025-03-30 16:45:00', 'draft');

-- Link posts to categories
INSERT INTO post_categories (post_id, category_id) VALUES
(1, 1), -- Test Post -> Web Development
(2, 3), -- Getting Started with Animation -> Animation
(3, 2), -- Responsive Design Techniques -> Design
(4, 1); -- Draft Post Example -> Web Development

-- Link posts to tags
INSERT INTO post_tags (post_id, tag_id) VALUES
(1, 1), -- Test Post -> PHP
(1, 5), -- Test Post -> MySQL
(2, 2), -- Getting Started with Animation -> JavaScript
(2, 3), -- Getting Started with Animation -> CSS
(3, 3), -- Responsive Design Techniques -> CSS
(3, 4), -- Responsive Design Techniques -> HTML
(3, 7), -- Responsive Design Techniques -> Responsive
(4, 1), -- Draft Post Example -> PHP
(4, 8); -- Draft Post Example -> Performance

-- Insert comments
INSERT INTO comments (post_id, user_id, content, status) VALUES
(1, 1, 'Great post! The web server is working perfectly.', 'approved'),
(2, 2, 'Thanks for the animation tips. Very helpful!', 'approved'),
(2, 1, 'I have a question about animation performance. Any tips?', 'approved'),
(3, 2, 'Responsive design is so important these days. Good article!', 'approved'),
(3, 1, 'I would add that testing on real devices is crucial.', 'pending');

-- Insert settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_title', 'Animate CMS', 'The title of the website'),
('site_description', 'A simple content management system for creating and managing blog posts', 'The description of the website'),
('posts_per_page', '10', 'Number of posts to display per page'),
('allow_comments', 'true', 'Whether to allow comments on posts'),
('moderate_comments', 'true', 'Whether to moderate comments before publishing');