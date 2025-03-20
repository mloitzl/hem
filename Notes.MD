# Build

```sh
$ docker buildx build --platform linux/amd64,linux/arm64 -f Dockerfile.heminit -t mloitzl/heminit:0.4.1 . --push
$ docker buildx build --platform linux/amd64,linux/arm64 -f Dockerfile.phpmyadmin -t mloitzl/phpmyadmin:2.11.11.3 . --push
$ docker buildx build --platform linux/amd64,linux/arm64 -f Dockerfile.hem -t mloitzl/hem:0.4.1 . --push

# --progress=plain --no-cache
```

# SQL User no Password

```sql
-- # Privileges for ``@`%`

GRANT ALL PRIVILEGES ON *.* TO ``@`%` WITH GRANT OPTION;

CREATE database hem;
GRANT ALL PRIVILEGES ON `hem`.* TO ``@`%`;

```

## User Management

```sql
-- Create user without password
GRANT ALL PRIVILEGES ON *.* TO 'pmauser'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
select Host, User, Password,Grant_priv from user;
````

- Connect from init container
```sh
mysql -h mysql -P 3306 -u "%"
```

```sh
# show users
SELECT DISTINCT user, host FROM mysql.user;

+------+-------------------------------+
| user | host                          |
+------+-------------------------------+
|      | %                             |
| root | %                             |
|      | buildkitsandbox               |
| root | buildkitsandbox               |
| root | hem-phpmyadmin-mysql-1.hem_db |
|      | localhost                     |
| root | localhost                     |
+------+-------------------------------+
7 rows in set (0.00 sec)

# show grants
SHOW GRANTS FOR ''@'%';

+---------------------------------------------------------+
| Grants for @%                                           |
+---------------------------------------------------------+
| GRANT ALL PRIVILEGES ON *.* TO ''@'%' WITH GRANT OPTION |
| GRANT ALL PRIVILEGES ON `test`.* TO ''@'%'              |
| GRANT ALL PRIVILEGES ON `hem`.* TO ''@'%'               |
| GRANT ALL PRIVILEGES ON `hem2`.* TO ''@'%'              |
| GRANT ALL PRIVILEGES ON `test\_%`.* TO ''@'%'           |
+---------------------------------------------------------+
5 rows in set (0.00 sec)



```

# Backup/Restore Volume

- Backup
```sh
docker run --rm -v hem_mysql:/data -v $(pwd):/backup alpine tar -czf /backup/hem_mysql_volume_backup.tar.gz -C /data .
```

- Restore:
```sh
docker run --rm -v hem_mysql:/data -v $(pwd):/backup alpine tar -xzf /backup/hem_mysql_volume_backup.tar.gz -C /data
```

- Check

```sh
docker run --rm -v hem_mysql:/data alpine ls -l /data
```

# Backup/Restore SQL

- Volume: backup: /backup/mysql/
- Backup

```sh
docker exec -it hem-mysql-1 /bin/bash
mysqldump hem > /backup/mysql/hem.sql
```
- Restore:
> Drop and recreate if necessary

```sql
DROP database hem;
CREATE database hem;
GRANT ALL PRIVILEGES ON `hem`.* TO ``@`%`;
```

```sh
mysql hem < /backup/mysql/hem.sql
```

# Up and down

```sh
 docker compose up && docker compose down -v
```