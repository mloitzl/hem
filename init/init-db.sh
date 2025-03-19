#!/bin/sh

echo "Checking if MySQL is ready..."
until mysqladmin ping -h ${MYSQL_HOST} --silent; do
    echo "Waiting for MySQL to start..."
    sleep 1s
done
echo "MySQL started"

# Check if database exists correctly in Alpine
if mysql -u "${MYSQL_INIT_USER}" -h ${MYSQL_HOST} -P 3306 -p${MYSQL_INIT_PASSWORD} -e "SHOW DATABASES;" | grep -q "${HEM_DB_NAME}"; then
    echo "Database ${HEM_DB_NAME} exists, nothing to do"
else
    echo "Database does not exist"
    echo "Creating G.O.D. User"
    mysql -u "${MYSQL_INIT_USER}" -h ${MYSQL_HOST} -P 3306 -p${MYSQL_INIT_PASSWORD} -e "GRANT ALL PRIVILEGES ON *.* TO ''@'%' WITH GRANT OPTION;FLUSH PRIVILEGES;"
    echo "Creating database ${HEM_DB_NAME}"
    mysql -u "${MYSQL_INIT_USER}" -h ${MYSQL_HOST} -P 3306 -p${MYSQL_INIT_PASSWORD} -e "CREATE DATABASE ${HEM_DB_NAME};"
    echo "Importing hem_backup.sql to ${HEM_DB_NAME}"
    mysql -u "${MYSQL_INIT_USER}" -h ${MYSQL_HOST} -P 3306 -p${MYSQL_INIT_PASSWORD} "${HEM_DB_NAME}" </hem_backup.sql
    echo "Database ${HEM_DB_NAME} restored with hem_backup.sql"
fi

if mysql -u "${MYSQL_INIT_USER}" -h ${MYSQL_HOST} -P 3306 -p${MYSQL_INIT_PASSWORD} -e "SHOW DATABASES;" | grep -q "phpmyadmin"; then
    echo "Database phpmyadmin exists, nothing to do"
else
    echo "Creating phpmyadmin user"
    mysql -u "${MYSQL_INIT_USER}" -h ${MYSQL_HOST} -P 3306 -p${MYSQL_INIT_PASSWORD} -e "GRANT ALL PRIVILEGES ON *.* TO '${PMA_USER}'@'%' WITH GRANT OPTION;FLUSH PRIVILEGES;"
    echo "Creating phpmyadmin database"
    mysql -u "${MYSQL_INIT_USER}" -h ${MYSQL_HOST} -P 3306 -p${MYSQL_INIT_PASSWORD} -e "CREATE DATABASE phpmyadmin;"
    echo "Importing create_tables_mysql_4_1_2+.sql to phpmyadmin"
    mysql -u "${MYSQL_INIT_USER}" -h ${MYSQL_HOST} -P 3306 -p${MYSQL_INIT_PASSWORD} </create_tables_mysql_4_1_2+.sql
fi

echo "Initialization complete."
exit 0
