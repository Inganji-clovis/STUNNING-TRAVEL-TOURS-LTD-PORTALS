# System Documentation: Stunning Travel & Tours Portal

## 🛠️ Architecture Overview
The application is deployed using a containerized **Docker** architecture. This ensures that the environment is identical across all office machines, regardless of local software installations like XAMPP.

### 1. Main Application Stack (`docker-compose.prod.yml`)
Consists of three interconnected services:
- **`app`**: Runs PHP 8.3-FPM. Handles the Laravel backend logic.
- **`web`**: Runs Nginx. Serves static files and proxies PHP requests to the `app` container.
- **`db`**: Runs MySQL 8.0. Stores all portal data with persistent volume storage in `mysql_data_prod`.

### 2. Security & SSL Stack (`nginx-proxy-manager-stack.yml`)
A separate "Traffic Controller" service that provides:
- **GUI Management**: Accessible via `http://localhost:81`.
- **SSL Certificates**: Automated Let's Encrypt certificates.
- **Proxying**: Maps user-friendly names (like `stunning.portal`) to the internal Docker IP.

### 3. Automation Layer (`launcher.ps1`)
A PowerShell script designed for non-technical users.
- **Verification**: Checks if Docker Desktop is running.
- **Startup**: Silently starts all required containers.
- **UI**: Launches a dedicated Chrome/Edge window in "App Mode" (isolated from other tabs).
- **Branding**: Automatically creates a "Stunning Portal" desktop shortcut with the official icon.

## 🚢 Deployment Requirements
- **OS**: Windows 10/11.
- **Prerequisite**: Docker Desktop installed and running.
- **Memory**: Minimum 4GB RAM (8GB recommended).

## 📡 Networking
The application runs inside a dedicated Docker bridge network (`stunning-network`). This allows containers to talk to each other (e.g., `app` connects to `db` using the hostname `db`) without exposing ports to the outside world unnecessarily.

---
*System Documentation Version 1.0*
