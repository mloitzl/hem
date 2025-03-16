# SQL User no PAssword

```sql
-- # Privileges for ``@`%`

GRANT ALL PRIVILEGES ON *.* TO ``@`%` WITH GRANT OPTION;

CREATE database hem;
GRANT ALL PRIVILEGES ON `hem`.* TO ``@`%`;

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
