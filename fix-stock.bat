@echo off
REM Update AC status reconciliation
REM Connect to MySQL and execute SQL statements

cd /d C:\xampp\mysql\bin

REM Execute SQL queries
mysql -u root rental_ac -e "UPDATE air_conditioners SET stock = 10, status = 'available' WHERE status = 'rented' AND id NOT IN (SELECT DISTINCT air_conditioner_id FROM rentals WHERE status IN ('active', 'confirmed'));"

mysql -u root rental_ac -e "SELECT id, model, stock, status FROM air_conditioners;"

echo.
echo Stock reconciliation completed!
pause
