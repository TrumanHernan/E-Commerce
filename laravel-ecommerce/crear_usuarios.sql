-- Script SQL para crear usuario administrador en NutriShop
-- Actualizado con hashes bcrypt válidos

-- Primero verifica si ya existe un usuario admin
SELECT * FROM users WHERE rol = 'admin';

-- Si no existe, crea un usuario admin
-- Contraseña: admin123
INSERT INTO users (name, email, email_verified_at, password, rol, created_at, updated_at)
VALUES (
    'Administrador',
    'admin@nutrishop.com',
    NOW(),
    '$2y$12$E9wqeZzIRnsd7m3oPTj8auHSKPX8bgSajSTd0NIunO6KZyFouy3p2',
    'admin',
    NOW(),
    NOW()
);

-- También puedes crear un usuario cliente de prueba
-- Contraseña: usuario123
INSERT INTO users (name, email, email_verified_at, password, rol, created_at, updated_at)
VALUES (
    'Usuario Prueba',
    'usuario@nutrishop.com',
    NOW(),
    '$2y$12$NUSpPnRrfwav3zONwXkGa.5Clo2uKu2RKecUYfbk.f1j0SDIl4IYa',
    'cliente',
    NOW(),
    NOW()
);

-- Verificar que se crearon correctamente
SELECT id, name, email, rol, created_at FROM users;
