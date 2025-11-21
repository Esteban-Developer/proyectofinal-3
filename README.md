#  Inferno Colombia - E-commerce de Moda

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"/>
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker"/>
  <img src="https://img.shields.io/badge/Jenkins-D24939?style=for-the-badge&logo=jenkins&logoColor=white" alt="Jenkins"/>
</p>

<p align="center">
  <strong>Plataforma e-commerce para venta de prendas de vestir</strong><br>
  Una solución completa de tienda online desarrollada para Inferno Colombia 🇨🇴
</p>

---

## 📋 Tabla de Contenidos

- [Descripción](#-descripción)
- [Características](#-características)
- [Tecnologías](#-tecnologías)
- [Requisitos Previos](#-requisitos-previos)
- [Instalación y Despliegue](#-instalación-y-despliegue)
- [Arquitectura](#-arquitectura)
- [Variables de Entorno](#-variables-de-entorno)
- [Autor](#-autor)
- [Licencia](#-licencia)

---

##  Descripción

**Inferno Colombia** es una plataforma de comercio electrónico diseñada para la venta de prendas de vestir. El sistema ofrece una experiencia de compra completa, desde la navegación del catálogo hasta el procesamiento seguro de pagos.

El proyecto está completamente dockerizado y desplegado en Docker Hub, lo que permite una instalación rápida y sencilla en cualquier entorno.

---

##  Características

| Funcionalidad | Descripción |
|---------------|-------------|
|  **Carrito de Compras** | Sistema completo para agregar, modificar y eliminar productos |
|  **Pasarela de Pagos** | Integración segura para procesar transacciones |
|  **Autenticación** | Sistema de registro e inicio de sesión de usuarios |
|  **Dashboard** | Panel con ofertas y productos destacados |
|  **Catálogo de Productos** | Visualización organizada de prendas de vestir |
|  **Diseño Responsive** | Interfaz adaptable a diferentes dispositivos |

---

##  Tecnologías

### Frontend
- HTML5
- CSS3
- JavaScript

### Backend
- PHP
- Laravel Framework

### Base de Datos
- MySQL

### DevOps & Despliegue
- Docker & Docker Compose
- Jenkins (CI/CD)
- Docker Hub

---

##  Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- [Docker](https://docs.docker.com/get-docker/) (v20.10 o superior)
- [Docker Compose](https://docs.docker.com/compose/install/) (v2.0 o superior)

Verifica la instalación:
```bash
docker --version
docker-compose --version
```

---

##  Instalación y Despliegue

### Paso 1: Crear el archivo Docker Compose

Crea un archivo llamado `docker-compose.yml` con el siguiente contenido, o puedes sacarlo directamente del repo el archivo se llama "docker-compose.yml":

```yaml
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
```

### Paso 2: Levantar el proyecto

```bash
docker-compose up -d
```

### Paso 3: Crear y configurar la red 

```bash
# Crear la red
docker network create threaderz-network

# Conectar los contenedores a la red
docker network connect threaderz-network threaderz_app
docker network connect threaderz-network threaderz_db

# Verificar la configuración de red
docker network inspect threaderz-network
```

### Paso 4: Acceder a la aplicación

Una vez desplegado, accede a la aplicación en tu navegador:

```
http://localhost:8080
```

---

##  Arquitectura

```
┌─────────────────────────────────────────────────────────┐
│                    Docker Network                        │
│                  (threaderz-network)                     │
│                                                          │
│  ┌──────────────────┐      ┌──────────────────┐         │
│  │                  │      │                  │         │
│  │   threaderz_app  │◄────►│   threaderz_db   │         │
│  │                  │      │                  │         │
│  │   Puerto: 8080   │      │   Puerto: 3306   │         │
│  │                  │      │                  │         │
│  └──────────────────┘      └──────────────────┘         │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

### Imágenes en Docker Hub

| Imagen | Descripción |
|--------|-------------|
| `esteban889/proyectofinal-3:latest` | Aplicación web (PHP/Laravel) |
| `esteban889/threaderz-mysql:latest` | Base de datos MySQL con datos precargados |

---

##  Variables de Entorno

### Aplicación
| Variable | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `DB_HOST` | Host de la base de datos | `db` |
| `DB_USER` | Usuario de la base de datos | `root` |
| `DB_PASS` | Contraseña de la base de datos | `12345` |
| `DB_NAME` | Nombre de la base de datos | `threaderz_store` |

### Base de Datos
| Variable | Descripción | Valor por defecto |
|----------|-------------|-------------------|
| `MYSQL_ROOT_PASSWORD` | Contraseña root de MySQL | `12345` |
| `MYSQL_DATABASE` | Nombre de la base de datos | `threaderz_store` |

---

##  Comandos Útiles

```bash
# Ver logs de los contenedores
docker-compose logs -f

# Detener los contenedores
docker-compose down

# Reiniciar los servicios
docker-compose restart

# Ver estado de los contenedores
docker ps

# Acceder al contenedor de la app
docker exec -it threaderz_app bash

# Acceder a la base de datos
docker exec -it threaderz_db mysql -u root -p
```

---

##  Autor

<p align="center">
  <strong>Esteban Murillo</strong><br>
  <em>Desarrollador</em>
</p>

<p align="center">
  <a href="https://github.com/esteban889">
    <img src="https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white" alt="GitHub"/>
  </a>
</p>



<p align="center">
  Hecho con ❤️ en Colombia 🇨🇴
</p>
