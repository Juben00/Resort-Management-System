CREATE EVENT Book
ON SCHEDULE EVERY 1 DAY
ON COMPLETION NOT PRESERVE
DO
    UPDATE bookings 
    SET booking_status_id = '4' 
    WHERE booking_end_date < CURDATE();

--- OR ---

CREATE EVENT Book
ON SCHEDULE EVERY 1 DAY
STARTS '2025-01-07 00:00:01'
ENDS '2026-01-07 00:00:01'
ON COMPLETION NOT PRESERVE
DO
    UPDATE bookings 
    SET booking_status_id = '4' 
    WHERE booking_end_date < CURDATE();