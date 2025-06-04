
# We News Symfony App

This is the **We News** Symfony application, containerized with Docker and managed by Docker Compose for easy development and deployment.

## Prerequisites

- [Docker] installed
- [Docker Compose] installed

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/nikolozradjke/we-news.git
cd we-news
```

### 2. Build and start the Docker containers

```bash
docker-compose up --build -d
```

### 3. Verify the containers are running

```bash
docker-compose ps
```

You should see containers for the app and database running.

### 4. Access the application

Open your browser and go to:

```
http://localhost:8000
```

Now you can run commands for migrations:

```bash
php bin/console doctrine:migrations:migrate
```

Now you can run commands for making admin user:

```bash
php bin/console app:create-admin
```


### 6. Stopping the containers

When you want to stop the app and remove containers, run:

```bash
docker-compose down
```