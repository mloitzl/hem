#!/bin/sh

echo "Checking if MySQL is ready..."
until mysqladmin ping -h mysql --silent; do
    echo "Waiting for MySQL to start..."
    sleep 1s
done
echo "MySQL started"

DB_NAME="hem2"
USER="testuser2"

# Check if database exists correctly in Alpine
if mysql -u "$USER" -h mysql -P 3306 -e "SHOW DATABASES;" | grep -q "$DB_NAME"; then
    echo "Database $DB_NAME exists"
else
    echo "Database does not exist"
    echo "Creating database $DB_NAME"
    mysql -u "$USER" -h mysql -P 3306 -e "CREATE DATABASE $DB_NAME;"
    echo "Database $DB_NAME created"
    mysql -u "$USER" -h mysql -P 3306 "$DB_NAME" </hem_backup.sql
    echo "Database $DB_NAME restored"
    mysql -u "$USER" -h mysql -P 3306 -e "GRANT ALL PRIVILEGES ON *.* TO 'pmauser'@'%' WITH GRANT OPTION;FLUSH PRIVILEGES;"
    mysql -u "$USER" -h mysql -P 3306 -e "CREATE DATABASE phpmyadmin;"
    mysql -u "$USER" -h mysql -P 3306 </create_tables_mysql_4_1_2+.sql
fi

echo "Initialization complete."
exit 0
