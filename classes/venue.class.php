<?php
require_once(__DIR__ . '/../dbconnection.php');

class Venue
{
    public $id;
    public $name;
    public $description;
    public $location;
    public $price;
    public $capacity;
    public $amenities;

    public $rules;
    public $entrance;
    public $cleaning;
    public $host_id = 2;
    public $status = 1;
    public $availability = 1;
    public $image_url;

    public $check_inout;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function addVenue()
    {
        try {
            // Establish database connection
            $conn = $this->db->connect();

            // Begin transaction
            $conn->beginTransaction();

            // Insert venue information
            $sql = 'INSERT INTO venues (name, description, location, price, capacity, amenities, rules, entrance, cleaning, time_inout, host_id, status_id, availability_id) 
                VALUES (:name, :description, :location, :price, :capacity, :amenities, :rules, :entrance, :cleaning, :time_inout, :host_id, :status_id, :availability_id)';
            $stmt = $conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':location', $this->location);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':capacity', $this->capacity);
            $stmt->bindParam(':amenities', $this->amenities);
            $stmt->bindParam(':rules', $this->rules);
            $stmt->bindParam(':entrance', $this->entrance);
            $stmt->bindParam(':cleaning', $this->cleaning);
            $stmt->bindParam(':time_inout', $this->check_inout);
            $stmt->bindParam(':host_id', $this->host_id);
            $stmt->bindParam(':status_id', $this->status);
            $stmt->bindParam(':availability_id', $this->availability);

            // Execute venue insertion
            if ($stmt->execute()) {
                // Get the last inserted ID for the venue
                $lastInsertedVenue = $conn->lastInsertId();

                // Insert images if available
                $images = json_decode($this->image_url);
                if ($images) {
                    $imageSql = "INSERT INTO venue_images (venue_id, image_url) VALUES (:venue_id, :image_url)";
                    $imageStmt = $conn->prepare($imageSql);

                    foreach ($images as $image_url) {
                        $imageStmt->bindParam(':venue_id', $lastInsertedVenue);
                        $imageStmt->bindParam(':image_url', $image_url);

                        // Execute image insertion
                        if (!$imageStmt->execute()) {
                            // If any image fails to insert, return error
                            return ['status' => 'error', 'message' => 'Failed to add images for the venue'];
                        }
                    }
                }
                $conn->commit();
                return ['status' => 'success', 'message' => 'Venue and images added successfully'];
            } else {
                $conn->rollBack();
                return ['status' => 'error', 'message' => 'Failed to add venue'];
            }

        } catch (PDOException $e) {
            // Log error and return failure message
            $conn->rollBack();
            $errmess = "Database error: " . $e->getMessage();
            error_log($errmess);  // Log the error message
            return ['status' => 'error', 'message' => $e->getMessage()];  // Return the error message
        }

    }
    function getAllVenues($status = '', $host_id = '', $bookmarks = [])
    {
        try {
            // Establish database connection
            $conn = $this->db->connect();

            // Start building the SQL query
            $sql = "SELECT 
            v.id AS venue_id,
            v.*, 
            vss.name AS status, 
            vas.name AS availability, 
            GROUP_CONCAT(vi.image_url) AS image_urls,
            AVG(r.rating) AS rating, 
            COUNT(DISTINCT r.id) AS total_reviews
            FROM venues v 
            JOIN venue_status_sub vss ON v.status_id = vss.id 
            JOIN venue_availability_sub vas ON v.availability_id = vas.id 
            JOIN venue_images vi ON v.id = vi.venue_id 
            LEFT JOIN reviews r ON v.id = r.venue_id";

            // Initialize an array for conditions and parameters
            $conditions = [];
            $params = [];

            // Add conditions if parameters are provided
            if ($status) {
                $conditions[] = "v.status_id LIKE :status";
                $params[':status'] = "%$status%";
            }

            if ($host_id) {
                $conditions[] = "v.host_id = :host_id";
                $params[':host_id'] = $host_id;
            }

            // Add WHERE clause if conditions are present
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            // Add GROUP BY clause
            $sql .= " GROUP BY v.id, vss.name, vas.name";

            // Prepare and execute the statement with parameters
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $venues = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process the images (split the comma-separated string into an array)
            foreach ($venues as &$venue) {
                if (!empty($venue['image_urls'])) {
                    $venue['image_urls'] = explode(',', $venue['image_urls']); // Convert image URLs to an array
                }
                // Check if the venue is bookmarked
                $venue['bookmarked'] = in_array($venue['venue_id'], $bookmarks);
            }

            return $venues;

        } catch (PDOException $e) {
            // Log error and return failure message
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    function getSingleVenue($venue_id = '')
    {
        try {
            // Establish database connection
            $conn = $this->db->connect();

            // Start building the SQL query
            $sql = "SELECT 
                v.id AS venue_id, 
                v.name AS venue_name, 
                v.description AS venue_description, 
                v.location AS venue_location, 
                v.*, 
                vss.name AS status, 
                vas.name AS availability, 
                AVG(r.rating) AS rating, 
                COUNT(DISTINCT r.id) AS total_reviews,
                GROUP_CONCAT(DISTINCT vi.image_url) AS image_urls
            FROM 
                venues v
            JOIN 
                venue_status_sub vss ON v.status_id = vss.id
            JOIN 
                venue_availability_sub vas ON v.availability_id = vas.id
            JOIN 
                venue_images vi ON v.id = vi.venue_id
            LEFT JOIN 
                reviews r ON v.id = r.venue_id
            WHERE 
                v.id = :venue_id
            GROUP BY 
                v.id, vss.name, vas.name;
        ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':venue_id', $venue_id);
            $stmt->execute();
            $venues = $stmt->fetch(PDO::FETCH_ASSOC);

            // Process the images (split the comma-separated string into an array)
            if (!empty($venues['image_urls'])) {
                $venues['image_urls'] = explode(',', $venues['image_urls']); // Convert image URLs to an array
            }

            return $venues;

        } catch (PDOException $e) {
            // Log error and return failure message
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    function getRatings($venue_id)
    {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT 
                AVG(rating) AS average,
                COUNT(rating) AS total,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) AS rating_5,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) AS rating_4,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) AS rating_3,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) AS rating_2,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) AS rating_1
            FROM reviews WHERE venue_id = :venue_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':venue_id', $venue_id);
            $stmt->execute();
            $ratings = $stmt->fetch(PDO::FETCH_ASSOC);
            return $ratings;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    function getReview($venue_id)
    {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT 
                r.id,
                r.review,
                r.rating,
                r.created_at AS date,
                u.id AS user_id,
                CONCAT(u.firstname, ' ', u.lastname) AS user_name,
                u.profile_pic AS profile_pic
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.venue_id = :venue_id
            ORDER BY date DESC;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':venue_id', $venue_id);
            $stmt->execute();
            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $reviews;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    function approveVenue($venue_id)
    {
        try {
            // Establish database connection
            $conn = $this->db->connect();

            // Update the venue status to approved
            $sql = "UPDATE venues SET status_id = 2 WHERE id = :venue_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':venue_id', $venue_id);

            // Execute the update and check if any rows were affected
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return ['status' => 'success', 'message' => 'Venue approved successfully'];
                } else {
                    return ['status' => 'error', 'message' => 'Venue ID not found or status already set to approved'];
                }
            } else {
                return ['status' => 'error', 'message' => 'Failed to approve venue'];
            }

        } catch (PDOException $e) {
            // Log error and return failure message
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    function declineVenue($venue_id)
    {
        try {
            // Establish database connection
            $conn = $this->db->connect();

            // Update the venue status to declined
            $sql = "UPDATE venues SET status_id = 3 WHERE id = :venue_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':venue_id', $venue_id);

            // Execute the update and check if any rows were affected
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return ['status' => 'success', 'message' => 'Venue declined successfully'];
                } else {
                    return ['status' => 'error', 'message' => 'Venue ID not found or status already set to declined'];
                }
            } else {
                return ['status' => 'error', 'message' => 'Failed to decline venue'];
            }

        } catch (PDOException $e) {
            // Log error and return failure message
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    function bookVenue(
        $booking_start_date,
        $booking_end_date,
        $booking_duration,
        $booking_status_id,
        $booking_participants,
        $booking_original_price,
        $booking_grand_total,
        $booking_guest_id,
        $booking_venue_id,
        $booking_discount,
        $booking_payment_method,
        $booking_payment_reference,
        $booking_payment_status_id,
        $booking_cancellation_reason = null,
        $booking_service_fee = null,
    ) {
        try {
            $conn = $this->db->connect();

            $sql = "INSERT INTO bookings (booking_start_date, booking_end_date, booking_duration, booking_status_id, booking_participants, booking_original_price, booking_grand_total, booking_guest_id, booking_venue_id, booking_discount, booking_payment_method, booking_payment_reference, booking_payment_status_id, booking_cancellation_reason, booking_service_fee) 
                VALUES (:booking_start_date, :booking_end_date, :booking_duration, :booking_status_id, :booking_participants, :booking_original_price, :booking_grand_total, :booking_guest_id, :booking_venue_id, :booking_discount, :booking_payment_method, :booking_payment_reference, :booking_payment_status_id, :booking_cancellation_reason, :booking_service_fee)";
            $stmt = $conn->prepare($sql);

            // Bind the parameters
            $stmt->bindParam(':booking_start_date', $booking_start_date);
            $stmt->bindParam(':booking_end_date', $booking_end_date);
            $stmt->bindParam(':booking_duration', $booking_duration);
            $stmt->bindParam(':booking_status_id', $booking_status_id);
            $stmt->bindParam(':booking_participants', $booking_participants);
            $stmt->bindParam(':booking_original_price', $booking_original_price);
            $stmt->bindParam(':booking_grand_total', $booking_grand_total);
            $stmt->bindParam(':booking_guest_id', $booking_guest_id);
            $stmt->bindParam(':booking_venue_id', $booking_venue_id);
            $stmt->bindParam(':booking_discount', $booking_discount);
            $stmt->bindParam(':booking_payment_method', $booking_payment_method);
            $stmt->bindParam(':booking_payment_reference', $booking_payment_reference);
            $stmt->bindParam(':booking_payment_status_id', $booking_payment_status_id);
            $stmt->bindParam(':booking_cancellation_reason', $booking_cancellation_reason);
            $stmt->bindParam(':booking_service_fee', $booking_service_fee);

            if ($stmt->execute()) {
                return ['status' => 'success', 'message' => 'Booking added successfully'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to add booking'];
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    function getBookings()
    {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT 
                    b.id AS booking_id,
                    b.booking_start_date,
                    b.booking_end_date,
                    b.booking_duration,
                    b.booking_participants,
                    b.booking_original_price,
                    b.booking_grand_total,
                    b.booking_discount,
                    b.booking_payment_method,
                    b.booking_payment_reference,
                    b.booking_service_fee,
                    b.booking_status_id,
                    b.booking_cancellation_reason,
                    b.booking_created_at,

                    u.id AS guest_id,
                    CONCAT(u.firstname, ' ', u.middlename, ' ', u.lastname) AS guest_name,
                    u.contact_number AS guest_contact_number,
                    u.email AS guest_email,
                    u.address AS guest_address,

                    v.id AS venue_id,
                    v.name AS venue_name,
                    v.location AS venue_location,
                    v.capacity AS venue_capacity,
                    v.price AS venue_price,
                    v.rules AS venue_rules,

                    d.discount_value AS discount_value


                FROM 
                    discounts d
                JOIN
                    bookings b ON d.discount_code = b.booking_discount
                JOIN 
                    users u ON b.booking_guest_id = u.id
                JOIN 
                    venues v ON b.booking_venue_id = v.id

                ORDER BY b.booking_created_at DESC;
                ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $bookings;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getAllDiscounts()
    {
        $query = "SELECT * FROM discounts";
        $result = $this->db->connect()->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveReservation($booking_id)
    {
        try {
            $conn = $this->db->connect();
            $sql = "UPDATE bookings SET booking_status_id = 2, booking_payment_status_id = 2 WHERE id = :booking_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':booking_id', $booking_id);

            if ($stmt->execute()) {
                return ['status' => 'success', 'message' => 'Reservation approved successfully'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to approve reservation'];
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function rejectReservation($booking_id)
    {
        try {
            $conn = $this->db->connect();
            $sql = "UPDATE bookings SET booking_status_id = 4 WHERE id = :booking_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':booking_id', $booking_id);

            if ($stmt->execute()) {
                return ['status' => 'success', 'message' => 'Reservation rejected successfully'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to reject reservation'];
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function cancelReservation($booking_id)
    {
        try {
            $conn = $this->db->connect();
            $sql = "UPDATE bookings SET booking_status_id = 3 WHERE id = :booking_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':booking_id', $booking_id);

            if ($stmt->execute()) {
                return ['status' => 'success', 'message' => 'Reservation cancelled successfully'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to cancel reservation'];
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getBookedDates($venue_id)
    {
        try {
            $conn = $this->db->connect();
            $STATUS_1 = 1;
            $STATUS_2 = 2;
            $sql = "SELECT booking_start_date AS startdate, booking_end_date AS enddate FROM bookings WHERE booking_venue_id = :venue_id AND booking_status_id IN (:status1, :status2)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':venue_id', $venue_id);
            $stmt->bindParam(':status1', $STATUS_1);
            $stmt->bindParam(':status2', $STATUS_2);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getAllBookings($userId = null, $status = null)
    {
        try {
            $conn = $this->db->connect();

            $sql = "SELECT 
    b.id AS booking_id,
    b.booking_start_date,
    b.booking_end_date,
    b.booking_duration,
    b.booking_participants,
    b.booking_original_price,
    b.booking_grand_total,
    b.booking_discount,
    b.booking_payment_method,
    b.booking_payment_reference,
    b.booking_service_fee,
    b.booking_status_id,
    b.booking_cancellation_reason,
    b.booking_created_at,

    u.id AS guest_id,
    CONCAT(u.firstname, ' ', COALESCE(u.middlename, ''), ' ', u.lastname) AS guest_name,
    u.contact_number AS guest_contact_number,
    u.email AS guest_email,
    u.address AS guest_address,

    v.id AS venue_id,
    v.name AS venue_name,
    v.location AS venue_location,
    v.capacity AS venue_capacity,
    v.price AS venue_price,
    v.rules AS venue_rules,

    d.discount_value AS discount_value,

    GROUP_CONCAT(COALESCE(vi.image_url, '')) AS image_urls
FROM 
    bookings b
LEFT JOIN
    discounts d ON d.discount_code = b.booking_discount
LEFT JOIN 
    users u ON b.booking_guest_id = u.id
LEFT JOIN 
    venues v ON b.booking_venue_id = v.id
LEFT JOIN 
    venue_images vi ON v.id = vi.venue_id
";

            // Filter conditions
            $conditions = [];
            $params = [];

            if ($userId) {
                $conditions[] = "b.booking_guest_id = :userId";
                $params[':userId'] = $userId;
            }
            if ($status) {
                $conditions[] = "b.booking_status_id LIKE :status";
                $params[':status'] = "%$status%";
            }

            // Append conditions to query
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }

            // Add GROUP BY clause
            $sql .= " GROUP BY b.id";

            // Add ORDER BY clause
            $sql .= " ORDER BY b.booking_created_at DESC";

            // Prepare and execute the statement
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            // Fetch and return results
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $bookings;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    function cancelBooking($bookingId, $reason)
    {
        try {
            $conn = $this->db->connect();
            $sql = "UPDATE bookings SET booking_status_id = 3, booking_cancellation_reason = :reason WHERE id = :booking_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':booking_id', $bookingId);
            $stmt->bindParam(':reason', $reason);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    function getBookingsByHost($host_id, $booking_status)
    {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT v.*, b.*, b.id AS booking_id, 
                GROUP_CONCAT(vi.image_url) AS image_urls
                FROM venues AS v
                JOIN bookings AS b 
                ON v.id = b.booking_venue_id 
                LEFT JOIN venue_images AS vi
                ON v.id = vi.venue_id
                WHERE v.host_id = :host_id AND b.booking_status_id = :booking_status
                GROUP BY b.id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':host_id', $host_id, PDO::PARAM_INT);
            $stmt->bindParam(':booking_status', $booking_status, PDO::PARAM_INT);
            $stmt->execute();
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $bookings;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    function getBookingByVenue($venue_id, $booking_status)
    {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT 
                COUNT(b.id) OVER() AS booking_count, 
                b.*, 
                u.*, 
                v.* 
            FROM bookings b
            JOIN users u ON b.booking_guest_id = u.id
            JOIN venues v ON b.booking_venue_id = v.id
            WHERE b.booking_venue_id = :venue_id AND b.booking_status_id = :status;
        ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':venue_id', $venue_id);
            $stmt->bindParam(':status', $booking_status);
            $stmt->execute();
            $booking = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows since multiple bookings may exist
            return $booking;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getDashboardStats() {
        try {
            $conn = $this->db->connect();
            
            // Get total reservations and their status counts
            $sql = "SELECT 
                COUNT(*) as total_reservations,
                SUM(CASE WHEN booking_status_id = 4 THEN 1 ELSE 0 END) as completed_reservations,
                SUM(CASE WHEN booking_status_id = 1 OR booking_status_id = 2 THEN 1 ELSE 0 END) as upcoming_reservations,
                SUM(CASE WHEN booking_status_id = 3 THEN 1 ELSE 0 END) as canceled_reservations,
                SUM(booking_grand_total) as total_earnings,
                SUM(CASE WHEN MONTH(booking_created_at) = MONTH(CURRENT_DATE) THEN booking_grand_total ELSE 0 END) as monthly_earnings
            FROM bookings";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);

            // Get new bookings in last 24 hours
            $sql = "SELECT COUNT(*) as new_bookings 
                    FROM bookings 
                    WHERE booking_created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $newBookings = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get upcoming reservations
            $sql = "SELECT b.*, v.name as venue_name, v.location as venue_location,
                        u.firstname, u.lastname, u.contact_number
                    FROM bookings b
                    JOIN venues v ON b.booking_venue_id = v.id
                    JOIN users u ON b.booking_guest_id = u.id
                    WHERE(b.booking_status_id = 1 OR b.booking_status_id = 2)
                    AND b.booking_start_date >= CURRENT_DATE
                    ORDER BY b.booking_start_date ASC
                    LIMIT 5";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $upcomingReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'reservations' => $stats,
                'new_bookings' => $newBookings['new_bookings'],
                'upcoming_reservations' => $upcomingReservations
            ];
            
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Error fetching dashboard statistics");
        }
    }

    public function getCompletedReservations() {
        $conn = $this->db->connect();
        $sql = "SELECT 
                    b.id,
                    CONCAT(u.firstname, ' ', u.lastname) as customer_name,
                    v.name,
                    b.booking_start_date AS booking_start_date,
                    b.booking_end_date AS booking_end_date,
                    b.booking_status_id
                FROM bookings b
                JOIN users u ON b.booking_guest_id = u.id
                JOIN venues v ON b.booking_venue_id = v.id
                WHERE b.booking_status_id = '4'
                ORDER BY b.booking_created_at DESC";
                
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

$venueObj = new Venue();

// var_dump($venueObj->getAllBookings(53));