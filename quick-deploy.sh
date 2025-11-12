#!/bin/bash

echo "🚀 Desplegando Threaderz Store (App + Base de Datos)..."
echo ""

# Crear directorio temporal
DEPLOY_DIR="/tmp/threaderz-deploy"
mkdir -p $DEPLOY_DIR
cd $DEPLOY_DIR

# Descargar docker-compose
echo "📥 Descargando configuración..."
cat > docker-compose.yml << 'EOF'
version: '3.8'

services:
  app:
    image: esteban889/proyectofinal-3:latest
    container_name: threaderz_app
    ports:
      - "8080:80"
    depends_on:
      - db
    environment:
      DB_HOST: db
      DB_USER: root
      DB_PASS: 12345
      DB_NAME: threaderz_store
    restart: always
    networks:
      - threaderz-network

  db:
    image: esteban889/threaderz-mysql:latest
    container_name: threaderz_db
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: 12345
      MYSQL_DATABASE: threaderz_store
    ports:
      - "3306:3306"
    networks:
      - threaderz-network
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:

networks:
  threaderz-network:
    driver: bridge
EOF

# Levantar servicios
echo "🐳 Iniciando contenedores..."
docker-compose pull
docker-compose up -d

# Esperar a que MySQL esté listo
echo "⏳ Esperando a que MySQL esté listo..."
sleep 20

echo ""
echo "✅ ¡Listo! Threaderz Store está corriendo"
echo "🌐 Abre tu navegador en: http://localhost:8080"
echo ""
echo "Para ver los logs: docker-compose logs -f"
echo "Para detener: docker-compose down"