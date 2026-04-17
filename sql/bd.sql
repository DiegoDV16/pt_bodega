-- TABLA ESTADO
CREATE TABLE estado (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(20) UNIQUE
);

-- INSERTAR ESTADOS
INSERT INTO estado (nombre) VALUES
('Activada'),
('Desactivada');

-- TABLA ENCARGADO
CREATE TABLE encargado (
    id SERIAL PRIMARY KEY,
    run VARCHAR(12),
    nombre VARCHAR(50),
    primer_apellido VARCHAR(50),
    segundo_apellido VARCHAR(50),
    direccion TEXT,
    telefono VARCHAR(20)
);

-- TABLA BODEGA
CREATE TABLE bodega (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(5) UNIQUE,
    nombre VARCHAR(100),
    direccion TEXT,
    dotacion INT,
    estado_id INT REFERENCES estado(id),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABLA RELACIÓN
CREATE TABLE bodega_encargado (
    id SERIAL PRIMARY KEY,
    bodega_id INT REFERENCES bodega(id),
    encargado_id INT REFERENCES encargado(id)
);

-- DATOS DE PRUEBA ENCARGADOS
INSERT INTO encargado (run, nombre, primer_apellido, segundo_apellido, direccion, telefono) VALUES
('12345678-9','Juan','Perez','Gonzalez','Puerto Montt','987654321'),
('11111111-1','Maria','Soto','Rojas','Osorno','912345678');