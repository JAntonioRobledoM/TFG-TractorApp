DROP DATABASE IF EXISTS TractorApp;
CREATE DATABASE IF NOT EXISTS TractorApp;
USE TractorApp;

-- 1. Tabla users (usuarios)
CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    dni VARCHAR(20) NULL,
    tlf BIGINT NULL,
    pass VARCHAR(255) NOT NULL,
    pfp VARCHAR(255) NULL,
    email VARCHAR(255) NULL,
    is_admin BOOLEAN NULL DEFAULT FALSE,
    remember_token VARCHAR(100) NULL, -- Añadido para soporte de "remember me"
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Tabla tractors (tractores)
CREATE TABLE tractors (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    field_2 BIGINT NULL,
    model VARCHAR(255) NULL,
    year INT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 3. Tabla users_tractors (relación entre usuarios y tractores)
CREATE TABLE users_tractors (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    tractor_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tractor_id) REFERENCES tractors(id) ON DELETE CASCADE
);

-- 4. Tabla listings (anuncios)
CREATE TABLE listings (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    tractor_id BIGINT NOT NULL,
    seller_id BIGINT NOT NULL,
    type VARCHAR(10) NOT NULL, -- 'sale' o 'rental'
    price DECIMAL(10, 2) NOT NULL,
    description TEXT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    start_date DATE NULL, -- para alquileres
    end_date DATE NULL, -- para alquileres
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tractor_id) REFERENCES tractors(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Tabla requests (solicitudes)
CREATE TABLE requests (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    listing_id BIGINT NOT NULL,
    requester_id BIGINT NOT NULL,
    type VARCHAR(10) NOT NULL, -- 'sale' o 'rental'
    status VARCHAR(20) NOT NULL, -- 'pending', 'accepted', 'rejected', 'completed'
    offered_price DECIMAL(10, 2) NULL,
    requested_start_date DATE NULL, -- para alquileres
    requested_end_date DATE NULL, -- para alquileres
    message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 6. Tabla notifications
CREATE TABLE notifications (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    related_id BIGINT NOT NULL, -- ID del elemento relacionado (solicitud, mensaje, etc.)
    related_type VARCHAR(50) NOT NULL, -- Tipo del elemento relacionado ('request', 'message', etc.)
    type VARCHAR(50) NOT NULL, -- Tipo de notificación
    message TEXT NOT NULL,
    is_read BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 7. Tabla messages
CREATE TABLE messages (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    sender_id BIGINT NOT NULL,
    receiver_id BIGINT NOT NULL,
    related_id BIGINT NULL, -- ID del elemento relacionado (puede ser un tractor, solicitud, etc.)
    related_type VARCHAR(50) NULL, -- Tipo del elemento relacionado
    content TEXT NOT NULL,
    is_read BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 8. Tabla conversations
CREATE TABLE conversations (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 9. Tabla conversation_user (pivot)
CREATE TABLE conversation_user (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    conversation_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 10. Tabla sessions (para Laravel)
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NULL,
    last_activity INT NOT NULL
);

-- 11. Tabla cache (para Laravel)
CREATE TABLE `cache` (
    `key` VARCHAR(255) PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL
);

-- 12. Tabla cache_locks (para Laravel)
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) PRIMARY KEY,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL
);

-- Índices adicionales para mejorar el rendimiento
CREATE INDEX idx_listings_seller ON listings(seller_id);
CREATE INDEX idx_listings_tractor ON listings(tractor_id);
CREATE INDEX idx_requests_listing ON requests(listing_id);
CREATE INDEX idx_requests_requester ON requests(requester_id);
CREATE INDEX idx_notification_user ON notifications(user_id);
CREATE INDEX idx_messages_sender ON messages(sender_id);
CREATE INDEX idx_messages_receiver ON messages(receiver_id);
CREATE INDEX idx_conversation_user ON conversation_user(conversation_id, user_id);
CREATE INDEX idx_sessions_user ON sessions(user_id);
CREATE INDEX idx_cache_expiration ON `cache`(expiration);
