# Delivery Express

## Project Description
Delivery Express is a web-based delivery management system built with PHP and MySQL. The application manages senders, receivers, packages, deliveries, and couriers.

## Requirements
- Docker Desktop
- Git
- A web browser

## How to Run the Project

### 1. Clone the Repository
```bash
git clone https://github.com/qaneanafeeza30-lgtm/delivery.git
cd delivery
```

### 2. Start the Application
Make sure Docker Desktop is running, then execute:

```bash
docker compose up -d --build
```

### 3. Set Up the Database
If the database has not been initialized, import the SQL file:

```powershell
Get-Content .\delivery_system.sql | docker compose exec -T db mysql -u root -proot delivery_db
```

### 4. Open the Application
Open your browser and visit:

http://localhost:8080

### 5. Check Container Status
```bash
docker compose ps
```

### 6. Stop the Application
```bash
docker compose down
```

## Troubleshooting
- Make sure Docker Desktop is running.
- If the application cannot connect to the database, check whether the database container is running.
- If port 8080 is already in use, check the port configuration in `compose.yml`.
- The database import command should only be run when the database needs initialization. If the database already contains tables, check before importing again.

## Team Collaboration
The source code is maintained in GitHub. Each team member can clone the repository and run the application locally using Docker.
