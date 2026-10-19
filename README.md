<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 📥 Descarga Directa de Documentación (.docx)

Haz clic en los siguientes enlaces para descargar la documentación completa lista para imprimir o presentar:

* 📄 [**Descargar Informe Técnico de Implementación (.docx)**](./docs/explicacion1.docx?raw=true)
* ⚙️ [**Descargar Guía de Configuración y Comandos (.docx)**](./docs/explicacion2.1.docx?raw=true)
* 🗄️ [**Descargar Script SQL y Procedimientos Almacenados (.docx)**](./docs/explicacion3.docx?raw=true)

---
# 🔐 TechAuth - Sistema de Autenticación, Recuperación de Contraseña y Passkeys

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Ngrok](https://img.shields.io/badge/Ngrok-111?style=for-the-badge&logo=ngrok&logoColor=white)

Sistema completo y robusto de autenticación desarrollado en **Laravel (API RESTful)** y **MySQL**, optimizado para entornos de escritorio y dispositivos móviles. Incorpora un flujo de recuperación de contraseña en tres pasos vía **SMTP (Gmail)** y la generación de códigos únicos de respaldo (**Passkeys**).

---

## 🚀 Características Principales

* **Autenticación Multi-factor / Passkey:** Soporte para inicio de sesión estándar y acceso alternativo mediante código único de respaldo (*Passkey*).
* **Recuperación en 3 Pasos:** Flujo dinámico desde `recuperar.html` (Solicitud de código ➔ Restablecimiento ➔ Generación opcional de nuevo Passkey).
* **Emails Responsivos en Dark Mode:** Plantilla HTML/Blade (`recuperacion.blade.php`) optimizada para evitar la inversión de colores en clientes de correo móviles.
* **Persistencia mediante Stored Procedures:** Seguridad e integridad en la base de datos utilizando Procedimientos Almacenados en MySQL.
* **Soporte Móvil vía Ngrok:** Integración con túneles HTTPS para pruebas en tiempo real desde redes móviles externas.

---

## 🛠️ Tecnologías Utilizadas

* **Backend:** Laravel 11.x (PHP 8.x) - Arquitectura API RESTful.
* **Base de Datos:** MySQL (`loginDB`) con Procedimientos Almacenados (*Stored Procedures*).
* **Notificaciones:** SMTP (Gmail) vía `Mailable` (`RecuperacionMail`).
* **Frontend:** HTML5, JavaScript (ES6+ `fetch` API), Tailwind CSS (Estilo Cyberpunk / Dark Mode).
* **Herramientas de Red:** Ngrok para túneles SSL/TLS.

---

## 🗄️ Estructura de la Base de Datos (`loginDB`)

Para inicializar la base de datos, ejecuta el archivo SQL adjunto en el proyecto o corre el siguiente script en tu gestor MySQL:

```sql
CREATE DATABASE IF NOT EXISTS loginDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE loginDB;

CREATE TABLE IF NOT EXISTS Usuario (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Nombres VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    Dni CHAR(8) NOT NULL UNIQUE,
    FechaNacimiento DATE NOT NULL,
    Edad INT NOT NULL,
    Telefono VARCHAR(20) NOT NULL,
    Correo VARCHAR(150) NOT NULL UNIQUE,
    Passwordd VARCHAR(255) NOT NULL,
    codigo_respaldo VARCHAR(50) DEFAULT NULL,
    codigo_recuperacion VARCHAR(10) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

Nota: Revisa los scripts de los 5 Procedimientos Almacenados (sp_Usuario_Guardar, sp_Usuario_Login, sp_Usuario_LoginCodigo, sp_Usuario_GuardarCodigo, sp_Usuario_UpdatePassword) incluidos en la documentación.

## ⚙️ Configuración e Instalación Local
1. Clonar el repositorio

```git clone [https://github.com/iDanorever/Login_Laravel.git](https://github.com/iDanorever/Login_Laravel.git)
cd Login_Laravel
```
---
## 2. Configurar Variables de Entorno
Copia el archivo .env.example a .env y configura tus credenciales locales:


```
cp .env.example .env
```

Configura tus credenciales SMTP de Gmail en el .env:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loginDB
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=tu_correo@gmail.com
MAIL_PASSWORD=tu_contraseña_de_aplicacion
MAIL_FROM_ADDRESS="tu_correo@gmail.com"
MAIL_FROM_NAME="TechAuth Security"
```
---

## 3. Iniciar el Servidor Laravel

```
php artisan config:clear
php artisan route:clear
php artisan serve --port=8000
```

## 4. Exponer con Ngrok (Para Pruebas en Celular)
En una segunda terminal:
```
ngrok http 8000
```
Copia la URL https://xxxx.ngrok-free.dev generada y pégala en el parámetro APP_URL de tu .env

---

🧑‍💻 Autor
Desarrollado por iDanorever
Desarrollador de Software
