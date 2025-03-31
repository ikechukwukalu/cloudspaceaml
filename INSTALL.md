# Moov Watchman Setup on Ubuntu

This guide documents the complete setup process for running the [Moov Watchman](https://github.com/moov-io/watchman) sanctions screening API using Docker, managed by Supervisor, and exposed via NGINX on port 80 using a reverse proxy.

---

## ✅ Requirements

- Ubuntu 20.04 or later
- Docker installed
- Docker Compose (v1 or v2)
- Supervisor installed
- NGINX installed

---

## 🐳 Step 1: Create a Docker Container with Volume Persistence

If you are not using Docker Compose, you can manually create the container once and manage it using Supervisor. This method ensures data persistence using Docker volumes:

```bash
docker create \
  --name moov-watchman \
  -p 127.0.0.1:8084:8084 \
  -v /moov-watchman-data:/root/.watchman \
  moov/watchman:latest
```

This setup:

- Binds container port 8084 to local port 8084
- Persists data in `/moov-watchman-data` on the host, mapped to the container’s internal `/root/.watchman` directory

---

## 🔧 Step 2: Create the Moov Watchman Docker Compose File (Alternative Setup)

Alternatively, if you prefer Docker Compose:

```bash
sudo mkdir -p /moov-watchman-aml
sudo nano /moov-watchman-aml/docker-compose.yml
```

Paste:

```yaml
version: '3.8'
services:
  watchman:
    image: moov/watchman:latest
    ports:
      - "127.0.0.1:8084:8084"
    volumes:
      - /moov-watchman-data:/root/.watchman
    restart: always
```

Then test it manually:

```bash
cd /moov-watchman-aml
sudo docker-compose up
```

If using Docker Compose v2:

```bash
docker compose up
```

---

## 🔁 Step 3: Set Up Supervisor

### ▶️ Option 1: If you used `docker create`

```ini
[program:moov-watchman-aml]
command=/usr/bin/docker start -a moov-watchman
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
redirect_stderr=true
stdout_logfile=/moov-watchman-aml/program.log
stopwaitsecs=3600
```

### ▶️ Option 2: If you're using Docker Compose

```ini
[program:moov-watchman-aml]
command=docker compose --file /moov-watchman-aml/docker-compose.yml up
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
redirect_stderr=true
stdout_logfile=/moov-watchman-aml/program.log
stopwaitsecs=3600
```

Then reload Supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start moov-watchman-aml
```

---

## 🌐 Step 4: Configure NGINX Reverse Proxy

Edit your NGINX default config:

```bash
sudo nano /etc/nginx/sites-available/default
```

Replace/add this block:

```nginx
server {
    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

    server_name venia.cloud;

    # Proxy all /moov-watchman-aml/* requests to the Docker backend
    location ^~ /moov-watchman-aml/ {
        proxy_pass http://127.0.0.1:8084/;  # Ensure trailing slash
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        proxy_connect_timeout 60;
        proxy_send_timeout 60;
        proxy_read_timeout 60;

        proxy_redirect off;
    }

    # Redirect everything else to HTTPS (optional)
    location / {
        return 301 https://$server_name$request_uri;
    }
}
```

Then reload NGINX:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

---

## ✅ Final Test

```bash
curl -v "http://your-server-ip/moov-watchman-aml/ping"
curl -v "http://your-server-ip/moov-watchman-aml/search?name=Nicolas+Maduro&type=person"
```

You should get a valid JSON response from Moov Watchman 🎉

---

## 🧠 Notes

- You can choose either a `docker create` + `start` approach with volumes, or Docker Compose
- Supervisor must track the foreground process, so do **not use `-d`** in the command
- If using Compose v2, use `docker compose` instead of `docker-compose`
- No need for a separate moov-watchman-aml.conf in NGINX — everything lives inside `default`
