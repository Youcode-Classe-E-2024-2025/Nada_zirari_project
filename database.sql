-- Active: 1733826049669@@127.0.0.1@3306@zirari_todo
CREATE DATABASE zirari_todo;

USE zirari_todo;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'member') DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    task_name TEXT NOT NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    assigned_to INT,
    project_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (project_id) REFERENCES projects(id)
);

ALTER TABLE projects
ADD COLUMN typeproject VARCHAR(255) NOT NULL ;
ALTER TABLE projects
ADD COLUMN is_public BOOLEAN DEFAULT 1;

INSERT INTO users (name, email, password, role) 
VALUES ('admin', 'zirari@admin.com', '123456', 'chef_de_projet');
ALTER TABLE users
MODIFY COLUMN role ENUM('chef_de_projet', 'membre', 'invite') NOT NULL DEFAULT 'membre';


INSERT INTO users (name, email, password, role) 
VALUES ('Chef de Projet', 'chef@gmail.com', SHA2('123456', 256), 'chef_de_projet');
INSERT INTO projects (name, description, is_public) VALUES
('Projet Alpha', 'Description du projet Alpha', 1),
('Projet Beta', 'Description du projet Beta', 0);
