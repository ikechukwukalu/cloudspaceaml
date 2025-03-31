# Moov Watchman Setup on Ubuntu

This guide documents the complete setup process for running the [Moov Watchman](https://github.com/moov-io/watchman) sanctions screening API using Docker, managed by Supervisor, and exposed via NGINX on port 80 using a reverse proxy.

---

## ✅ Requirements

- Ubuntu 20.04 or later
- Docker installed
- Docker Compose Plugin (v2) installed
- Supervisor installed
- NGINX installed

---

## ⚙️ Install Docker Compose Plugin (v2)

Docker Compose v2 is included as a plugin to Docker Engine. If it's missing, you can install it manually:

```bash
mkdir -p ~/.docker/cli-plugins/
curl -SL https://github.com/docker/compose/releases/download/v2.27.1/docker-compose-linux-x86_64 -o ~/.docker/cli-plugins/docker-compose
chmod +x ~/.docker/cli-plugins/docker-compose
```

Then test:

```bash
docker compose version
```

You should see something like `Docker Compose version v2.27.1`

---

## 🐳 Step 1: Create the Moov Watchman Docker Compose File

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
sudo docker compose up
```

---

## 🔁 Step 2: Set Up Supervisor

Create the Supervisor configuration file:

```bash
sudo nano /etc/supervisor/conf.d/moov-watchman-aml.conf
```

Paste:

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

Then apply the configuration:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start moov-watchman-aml
```

---

## 🌐 Step 3: Configure NGINX Reverse Proxy

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

- Docker Compose must not use `-d` when controlled by Supervisor
- Supervisor must track the foreground process to restart it properly
- This setup uses Docker Compose Plugin (v2) via `docker compose`
- No need for a separate moov-watchman-aml.conf in NGINX — everything lives inside `default`
