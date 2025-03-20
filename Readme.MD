# Objectives

- This is my master's thesis project from 2005.
- Recreated with legacy builds in 2025 -- 20 years later.

# Start


```sh
$ git clone https://github.com/mloitzl/hem.git
$ cd ./hem
$ docker compose up

# Should produce:

 ✔ Network hem_db              Created                                                                               0.0s
 ✔ Volume "hem_mysql"          Created                                                                               0.0s
 ✔ Container hem-mysql-1       Created                                                                               0.2s
 ✔ Container hem-phpmyadmin-1  Created                                                                               0.3s
 ✔ Container hem-hem-1         Created                                                                               0.3s
 ✔ Container hem-init-1        Created                                                                               0.1s
Attaching to hem-1, init-1, mysql-1, phpmyadmin-1
mysql-1       | Starting MySQL in the background...
mysql-1       | Waiting for MySQL to start...
mysql-1       | Starting mysqld daemon with databases from /usr/local/mysql/var
mysql-1       | mysqld is alive
mysql-1       | MySQL started!
mysql-1       | Creating user 'MYSQL_INIT_USER'...
mysql-1       | User 'MYSQL_INIT_USER' created successfully!
init-1        | Checking if MySQL is ready...
init-1        | MySQL started
init-1        | Database does not exist
init-1        | Creating G.O.D. User
phpmyadmin-1  | httpd: Could not reliably determine the server's fully qualified domain name, using 172.18.0.5 for ServerName
hem-1         | httpd: Could not reliably determine the server's fully qualified domain name, using 172.18.0.3 for ServerName
init-1        | Creating database hem
init-1        | Importing hem_backup.sql to hem
init-1        | Database hem restored with hem_backup.sql
init-1        | Creating phpmyadmin user
init-1        | Creating phpmyadmin database
init-1        | Importing create_tables_mysql_4_1_2+.sql to phpmyadmin
init-1        | Initialization complete.
init-1 exited with code 0
```

Point your Browser to:
- hem 0.4.1: http://localhost:9080/
- phpMyAdmin 2.11.11.3: http://localhost:9081/

# Infrastructure

## ARM64

- mysql 4.1.22: [Dockerfile](https://github.com/mloitzl/php4-mysql4-apache2.2-docker/blob/master/Dockerfile.alpine.mysql.arm64)
- php 4.4.9 and apache 2.2.34: [Dockerfile](https://github.com/mloitzl/php4-mysql4-apache2.2-docker/blob/master/Dockerfile.alpine.arm64)

> Do not use this in production!
>> -- Lemmy Kilmister