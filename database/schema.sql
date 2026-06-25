CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE books (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE articles (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE researchers (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  bio TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_books (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  book_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (book_id),
  CONSTRAINT fk_user_books_user FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_books_book FOREIGN KEY (book_id) REFERENCES books (id)
);

CREATE TABLE user_articles (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  article_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (article_id),
  CONSTRAINT fk_user_articles_user FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_articles_article FOREIGN KEY (article_id) REFERENCES articles (id)
);

CREATE TABLE user_researchers (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  researcher_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (researcher_id),
  CONSTRAINT fk_user_researchers_user FOREIGN KEY (user_id) REFERENCES users (id),
  CONSTRAINT fk_user_researchers_researcher FOREIGN KEY (researcher_id) REFERENCES researchers (id)
);

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO books (title, author, description) VALUES
  ('كتاب 1', 'مؤلف 1', 'وصف كتاب 1'),
  ('كتاب 2', 'مؤلف 2', 'وصف كتاب 2');

INSERT INTO articles (title, content) VALUES
  ('مقاله 1', 'محتوى مقاله 1'),
  ('مقاله 2', 'محتوى مقاله 2');

INSERT INTO researchers (name, bio) VALUES
  ('باحث 1', 'وصف باحث 1'),
  ('باحث 2', 'وصف باحث 2');