-- Fix AC Stock Reconciliation
USE rental_ac;

-- Update AC yang tidak punya rental aktif: set stock = 10, status = available
UPDATE air_conditioners 
SET stock = 10, status = 'available' 
WHERE id NOT IN (
    SELECT DISTINCT air_conditioner_id 
    FROM rentals 
    WHERE status IN ('active', 'confirmed')
);

-- Update AC yang punya rental aktif: set status = rented
UPDATE air_conditioners 
SET status = 'rented' 
WHERE id IN (
    SELECT DISTINCT air_conditioner_id 
    FROM rentals 
    WHERE status IN ('active', 'confirmed')
);

-- Show result
SELECT id, model, stock, status FROM air_conditioners;
