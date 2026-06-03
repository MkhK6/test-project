#!/bin/bash
set -e

TEST_DB_NAME="${POSTGRES_TEST_DB:-laravel_test}"

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    SELECT format('CREATE DATABASE %I', '${TEST_DB_NAME}')
    WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '${TEST_DB_NAME}')\gexec
EOSQL
