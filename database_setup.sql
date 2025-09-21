-- Script para configurar o banco de dados no XAMPP (MySQL/MariaDB)
-- Execute este script no phpMyAdmin ou na linha de comando do MySQL

-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS banco_inter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar o banco de dados
USE banco_inter;

-- Verificar se o banco foi criado
SHOW TABLES;

-- Mostrar informações do banco
SELECT 'Banco de dados banco_inter criado com sucesso!' as status;

