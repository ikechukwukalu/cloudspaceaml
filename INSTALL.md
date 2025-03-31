# Moov Watchman Integration with NGINX + Docker + Supervisor

This guide documents the complete setup process for running the [Moov Watchman](https://github.com/moov-io/watchman) sanctions screening API using Docker, managed by Supervisor, and exposed via NGINX on port 80 using a reverse proxy.

---

## ✅ Requirements

- Ubuntu 20.04 or later
- Docker installed
- Supervisor installed
- NGINX installed

---

## 🔧 Step 1: Create the Moov Watchman Docker Container

We start by creating the container in a stable way so Supervisor can manage it properly.

```bash
docker create \
  --name moov-watchman \
  -p 127.0.0.1:8084:8084 \
  moov/watchman:latest
```

This command:

- Creates a reusable container named `moov-watchman`
- Binds container port 8084 to `127.0.0.1:8084` (local-only)

---

## 🔁 Step 2: Set Up Supervisor

Create a Supervisor config file at:

```bash
sudo nano /etc/supervisor/conf.d/moov-watchman-aml.conf
```

Paste the following:

```ini
[program:moov-watchman-aml]
command=/usr/bin/docker start -a moov-watchman
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=kalu
redirect_stderr=true
stdout_logfile=/moov-watchman-aml/program.log
stopwaitsecs=3600
```

> 💡 Adjust the `user` if necessary

Then reload Supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start moov-watchman-aml
```

To view logs:

```bash
tail -f /moov-watchman-aml/program.log
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

- All logic now lives inside the default NGINX block under `/moov-watchman-aml/`
- `/moov-watchman-aml/` must be preserved when calling the API unless you rewrite it
