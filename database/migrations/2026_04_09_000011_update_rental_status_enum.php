<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support ENUM and MODIFY directly, so use conditional logic
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite: recreate table with new column definition
            DB::statement("PRAGMA foreign_keys=OFF");
            
            // Drop rentals_new table if it exists from previous failed migration
            DB::statement("DROP TABLE IF EXISTS rentals_new");
            
            DB::statement("
                CREATE TABLE rentals_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER,
                    air_conditioner_id INTEGER,
                    quantity INTEGER,
                    rental_start DATETIME,
                    rental_end DATETIME,
                    rental_type VARCHAR(255),
                    total_price DECIMAL(12,2),
                    status VARCHAR(255) DEFAULT 'confirmed' CHECK(status IN ('confirmed', 'completed', 'cancelled')),
                    customer_name VARCHAR(255),
                    customer_email VARCHAR(255),
                    customer_phone VARCHAR(255),
                    customer_address TEXT,
                    notes TEXT,
                    payment_method VARCHAR(255),
                    payment_status VARCHAR(255),
                    payment_reference VARCHAR(255),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP,
                    FOREIGN KEY(user_id) REFERENCES users(id),
                    FOREIGN KEY(air_conditioner_id) REFERENCES air_conditioners(id)
                )
            ");
            
            DB::statement("
                INSERT INTO rentals_new 
                SELECT id, user_id, air_conditioner_id, quantity, rental_start, rental_end, 
                       rental_type, total_price, status, customer_name, customer_email, 
                       customer_phone, customer_address, notes, payment_method, 
                       payment_status, payment_reference, created_at, updated_at 
                FROM rentals
            ");
            
            DB::statement("DROP TABLE rentals");
            DB::statement("ALTER TABLE rentals_new RENAME TO rentals");
            DB::statement("PRAGMA foreign_keys=ON");
        } else {
            // For MySQL: use MODIFY ENUM
            DB::statement("ALTER TABLE rentals MODIFY status ENUM('confirmed', 'completed', 'cancelled') DEFAULT 'confirmed'");
        }
        
        // Update existing 'active' records to 'confirmed'
        DB::table('rentals')->where('status', 'active')->update(['status' => 'confirmed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // SQLite doesn't support ENUM and MODIFY directly, so use conditional logic
        if (DB::getDriverName() === 'sqlite') {
            // For SQLite: recreate table with old column definition
            DB::statement("PRAGMA foreign_keys=OFF");
            
            DB::statement("
                CREATE TABLE rentals_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER,
                    air_conditioner_id INTEGER,
                    quantity INTEGER,
                    rental_start DATETIME,
                    rental_end DATETIME,
                    rental_type VARCHAR(255),
                    total_price DECIMAL(12,2),
                    status VARCHAR(255) DEFAULT 'active' CHECK(status IN ('active', 'completed', 'cancelled')),
                    customer_name VARCHAR(255),
                    customer_email VARCHAR(255),
                    customer_phone VARCHAR(255),
                    customer_address TEXT,
                    notes TEXT,
                    payment_method VARCHAR(255),
                    payment_status VARCHAR(255),
                    payment_reference VARCHAR(255),
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP,
                    FOREIGN KEY(user_id) REFERENCES users(id),
                    FOREIGN KEY(air_conditioner_id) REFERENCES air_conditioners(id)
                )
            ");
            
            DB::statement("
                INSERT INTO rentals_new 
                SELECT id, user_id, air_conditioner_id, quantity, rental_start, rental_end, 
                       rental_type, total_price, status, customer_name, customer_email, 
                       customer_phone, customer_address, notes, payment_method, 
                       payment_status, payment_reference, created_at, updated_at 
                FROM rentals
            ");
            
            DB::statement("DROP TABLE rentals");
            DB::statement("ALTER TABLE rentals_new RENAME TO rentals");
            DB::statement("PRAGMA foreign_keys=ON");
        } else {
            // For MySQL: use MODIFY ENUM
            DB::statement("ALTER TABLE rentals MODIFY status ENUM('active', 'completed', 'cancelled') DEFAULT 'active'");
        }
        
        DB::table('rentals')->where('status', 'confirmed')->update(['status' => 'active']);
    }
};
