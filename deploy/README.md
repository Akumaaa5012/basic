# Running SproutRoute on Ubuntu Server

Tested on Ubuntu 24.04 LTS. 22.04 should work too. You get Apache, PHP 8 and MySQL 8, all on one machine.

## 1. Get the code onto the server

```bash
sudo apt-get update && sudo apt-get install -y git
git clone https://github.com/akumaaa5012/basic.git
cd basic
```

## 2. Run the setup script

Pick one:

```bash
# A) No domain: the site is served on the server's IP address
sudo bash deploy/setup.sh

# B) You have a domain pointing at this server
sudo DOMAIN=sproutroute.example.com bash deploy/setup.sh

# C) A domain plus a free HTTPS certificate (recommended)
sudo DOMAIN=sproutroute.example.com EMAIL=you@example.com HTTPS=1 bash deploy/setup.sh
```

When it finishes it prints the address to open.

The script does the following:

- Installs Apache, PHP (with mysqli) and MySQL.
- Copies the app to `/var/www/sproutroute`.
- Creates the `sproutroute` database and imports `database/sproutroute.sql`. It does this only the first time.
- Creates a MySQL user called `sproutroute` with a random password. That password goes in `/var/www/sproutroute/api/config.local.php`, which is never committed.
- Lets Apache write to `api/uploads/products/` so product photos can be uploaded. PHP files in that folder are blocked from running.
- Blocks web access to `.git`, `database/`, `deploy/`, `*.md`, `*.sql` and the config file.
- Opens ports 80 and 443 if the `ufw` firewall is on.

## 3. Log in

All demo accounts use the password `demo123`:

| Portal    | Email                  |
|-----------|------------------------|
| Seller    | `farmer1@example.test` |
| Buyer     | `resto@example.test`   |
| Logistics | `driver@example.test`  |

**Change these passwords, or delete these accounts, before real people use the site.**

## Updating after new commits

```bash
cd basic
git pull
sudo bash deploy/setup.sh
```

Running the script again updates the code. It keeps the database, the DB password and the uploaded photos.

If changes to pages don't show up, bump `CACHE_NAME` in `sw.js`. The service worker caches the pages, and changing that name makes browsers fetch fresh copies.

## Notes

- **HTTPS matters.** Browsers run the service worker (offline mode and "Install app") only over HTTPS or on `localhost`. Over plain `http://<ip>` the site works, but without the offline features. Use option C to get them.
- **Local WAMP/XAMPP still works.** If there is no `config.local.php`, `api/db.php` falls back to `root` with an empty password on `localhost`.
- **Logs:** `/var/log/apache2/sproutroute-error.log`
- **Database shell:** `sudo mysql sproutroute`
- **Backup:** `sudo mysqldump sproutroute > backup.sql`
- **Firewall**, if not enabled yet: `sudo ufw allow OpenSSH && sudo ufw allow 'Apache Full' && sudo ufw enable`
