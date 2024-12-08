<?php
require_once(__DIR__ . '/../dbconnection.php');

class Account
{
    public $id;
    public $firstname;
    public $lastname;
    public $middlename;
    public $sex;
    public $usertype;
    public $birthdate;
    public $address;
    public $contact_number;
    public $email;
    public $password;

    //host account table variables

    public $userId;
    public $fullname;
    public $status_id = 1;
    public $idOne_type;
    public $idOne_url;
    public $idTwo_type;
    public $idTwo_url;

    protected $db;

    function __construct()
    {
        $this->db = new Database();
    }

    public function signup()
    {
        try {

            // Check if the email already exists
            $sql = 'SELECT * FROM users WHERE email = :email';
            $chkstmt = $this->db->connect()->prepare($sql);
            $chkstmt->bindParam(':email', $this->email);
            $chkstmt->execute();

            if ($chkstmt->rowCount() > 0) {
                return ['status' => 'error', 'message' => 'Account already exists'];
            } else {
                // Insert new account if email does not exist
                $sql = 'INSERT INTO users (firstname, lastname, middlename, sex_id, user_type_id, birthdate, contact_number, address ,email, password) VALUES (:firstname, :lastname, :middlename, :sex_id, :user_type_id, :birthdate, :contact_number,:address, :email, :password)';
                $stmt = $this->db->connect()->prepare($sql);

                $stmt->bindParam(':firstname', $this->firstname);
                $stmt->bindParam(':lastname', $this->lastname);
                $stmt->bindParam(':middlename', $this->middlename);

                // Determine sex_id based on sex value
                $sex_id = 3; // Default value for unspecified sex
                if ($this->sex == "Male") {
                    $sex_id = 1;
                } else if ($this->sex == "Female") {
                    $sex_id = 2;
                }
                $stmt->bindParam(':sex_id', $sex_id);
                $stmt->bindParam(':user_type_id', $this->usertype);
                $stmt->bindParam(':birthdate', $this->birthdate);
                $stmt->bindParam(':contact_number', $this->contact_number);
                $stmt->bindParam(':address', $this->address);
                $stmt->bindParam(':email', $this->email);

                // Hash the password before saving to the database
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);

                $stmt->bindParam(':password', $this->password);

                if ($stmt->execute()) {
                    return ['status' => 'success', 'message' => 'Account created successfully'];
                } else {
                    return ['status' => 'error', 'message' => 'Failed to create account'];
                }
            }
        } catch (PDOException $e) {
            // Log error and return empty array
            error_log("Error creating account: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    public function login()
    {
        try {

            $sql = 'SELECT * FROM users WHERE email = :email';
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($this->password, $user['password'])) {

                    session_start();
                    session_regenerate_id(delete_old_session: true);
                    $_SESSION['user'] = $user;

                    return ['status' => 'success', 'message' => 'Login successful', 'user' => $user];
                } else {
                    return ['status' => 'error', 'message' => 'Invalid email or password'];
                }
            } else {
                return ['status' => 'error', 'message' => 'Invalid email or password'];
            }
        } catch (PDOException $e) {
            // Log error and return empty array
            error_log("Error fetching user data: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function retrieveUser($id)
    {
        try {

            // Add wildcards to user_id and user_type for LIKE search
            $user_id = "%" . $id . "%";

            // SQL query to fetch user data along with their sex and user type
            $sql = 'SELECT u.*, ss.name AS sex, ust.name AS user_type 
            FROM users u 
            JOIN sex_sub ss ON u.sex_id = ss.id 
            JOIN user_types_sub ust ON ust.id = u.user_type_id 
            WHERE u.id LIKE :user_id';

            // Prepare the SQL statement
            $stmt = $this->db->connect()->prepare($sql);

            // Bind the parameters with wildcards for LIKE clause
            $stmt->bindParam(':user_id', $user_id);

            // Execute the SQL query
            $stmt->execute();

            // Fetch the user data as an associative array
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the user data
            return $user;
        } catch (PDOException $e) {
            // Log error and return empty array
            error_log("Error fetching user data: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getUser($user_id = '', $user_type = '')
    {
        try {

            // Add wildcards to user_id and user_type for LIKE search
            $user_id = "%" . $user_id . "%";
            $user_type = "%" . $user_type . "%";

            // SQL query to fetch user data along with their sex and user type
            $sql = 'SELECT u.*, ss.name AS sex, ust.name AS user_type 
            FROM users u 
            JOIN sex_sub ss ON u.sex_id = ss.id 
            JOIN user_types_sub ust ON ust.id = u.user_type_id 
            WHERE u.id LIKE :user_id AND u.user_type_id LIKE :user_type';

            // Prepare the SQL statement
            $stmt = $this->db->connect()->prepare($sql);

            // Bind the parameters with wildcards for LIKE clause
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':user_type', $user_type);

            // Execute the SQL query
            $stmt->execute();

            // Fetch the user data as an associative array
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the user data
            return $user;
        } catch (PDOException $e) {
            // Log error and return empty array
            error_log("Error fetching user data: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function upgradeUser()
    {
        try {
            $conn = $this->db->connect();

            // Begin transaction
            $conn->beginTransaction();

            // Check if the user has already applied
            $checkSql = "SELECT userId FROM host_application WHERE userId = :userId;";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':userId', $this->userId);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                return ['status' => 'error', 'message' => 'You already applied for a Host Account, or your request is pending approval.'];
            }

            // Insert new host application
            $sql = "INSERT INTO host_application (userId, fullname, address, birthdate, status_id) VALUES (:userId, :fullname, :address, :birthdate, :status_id)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':userId', $this->userId);
            $stmt->bindParam(':fullname', $this->fullname);
            $stmt->bindParam(':address', $this->address);
            $stmt->bindParam(':birthdate', $this->birthdate);
            $stmt->bindParam(':status_id', $this->status_id);

            if ($stmt->execute()) {
                $lastInsertedHostId = $conn->lastInsertId();
                $imgOne = $this->idOne_url;
                $imgTwo = $this->idTwo_url;

                if ($imgOne && $imgTwo) {
                    $imageSql = "INSERT INTO host_id_images (id, idOne_type, idOne_image_url, idTwo_type, idTwo_image_url) VALUES (:id, :idOne_type, :idOne_image_url, :idTwo_type, :idTwo_image_url)";
                    $imageStmt = $conn->prepare($imageSql);

                    $imageStmt->bindParam(':id', $lastInsertedHostId);
                    $imageStmt->bindParam(':idOne_type', $this->idOne_type);
                    $imageStmt->bindParam(':idOne_image_url', $imgOne);
                    $imageStmt->bindParam(':idTwo_type', $this->idTwo_type);
                    $imageStmt->bindParam(':idTwo_image_url', $imgTwo);

                    if (!$imageStmt->execute()) {
                        $conn->rollBack();
                        return ['status' => 'error', 'message' => 'Failed to add images for the host application'];
                    }
                }

                // Commit transaction
                $conn->commit();
                return ['status' => 'success', 'message' => 'Host application sent successfully'];
            } else {
                $conn->rollBack();
                return ['status' => 'error', 'message' => 'Failed to send host application'];
            }

        } catch (PDOException $e) {
            $conn->rollBack();
            // $errMessage = "Database error: " . $e->getMessage();
            // error_log($errMessage); // Log the error message
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function HostApplicationStats($userId, $status)
    {
        try {

            // Establish database connection
            $conn = $this->db->connect();

            // Prepare the status filter for LIKE query
            $statusLike = "%" . $status . "%";

            // SQL query to fetch data
            $sql = "SELECT * FROM host_application WHERE userId = :userId AND status_id LIKE :status;";

            // Prepare statement
            $stmt = $conn->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':status', $statusLike);

            // Execute the query
            if ($stmt->execute()) {
                return $stmt->rowCount() > 0; // Return true if records found, false if not
            }
            return false;
        } catch (PDOException $e) {
            // Log error and return false
            error_log("Error fetching host application stats: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getHostApplications($userId = null, $status = null)
    {
        try {
            $conn = $this->db->connect();

            // Build query
            $sql = "SELECT ha.*, hs.name AS status, hi.idOne_image_url AS idOne, hi.idTwo_image_url AS idTwo
                FROM host_application ha 
                JOIN host_application_status_sub hs ON ha.status_id = hs.id 
                LEFT JOIN host_id_images hi ON ha.id = hi.id";

            $conditions = [];
            $params = [];

            if (!empty($userId)) {
                $conditions[] = "ha.userId = :userId";
                $params[':userId'] = $userId;
            }

            if (!empty($status)) {
                $conditions[] = "ha.status_id LIKE :status";
                $params[':status'] = "%" . $status . "%";
            }

            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }

            // Prepare and execute statement
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Log error and return empty array
            error_log("Error fetching host applications: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function approveHost($host_id)
    {
        try {
            $conn = $this->db->connect();
            $conn->beginTransaction();

            // Update host application status
            $sql = "UPDATE host_application SET status_id = 2 WHERE userId = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $host_id);
            if (!$stmt->execute()) {
                throw new Exception('Failed to update host application status');
            }

            // Update host account
            $sql = "UPDATE users SET user_type_id = 1 WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $host_id);
            if (!$stmt->execute()) {
                throw new Exception('Failed to update user type');
            }

            // Commit transaction
            $conn->commit();
            return ['status' => 'success', 'message' => 'Host application approved successfully'];
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error approving host application: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function rejectHost($host_id)
    {
        try {
            $conn = $this->db->connect();
            $conn->beginTransaction();

            // Update host application status
            $sql = "UPDATE host_application SET status_id = 3 WHERE userId = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $host_id);
            if (!$stmt->execute()) {
                throw new Exception('Failed to update host application status');
            }

            // Update host account
            $sql = "UPDATE users SET user_type_id = 2 WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $host_id);
            if (!$stmt->execute()) {
                throw new Exception('Failed to update user type');
            }

            // Commit transaction
            $conn->commit();
            return ['status' => 'success', 'message' => 'Host application Rejected successfully'];
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error approving host application: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function addBookmark($userId = "", $venueId = "")
    {
        try {
            $conn = $this->db->connect();
            $conn->beginTransaction();

            $checkSql = "SELECT * FROM bookmarks WHERE userId = :userId AND venueId = :venueId";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':userId', $userId);
            $checkStmt->bindParam(':venueId', $venueId);

            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $sql = "DELETE FROM bookmarks WHERE userId = :userId AND venueId = :venueId";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':venueId', $venueId);
                if (!$stmt->execute()) {
                    $conn->rollBack();
                    return ['status' => 'error', 'message' => 'Venue was not unbookmarked.'];
                } else {
                    $conn->commit();
                    return ['status' => 'success', 'message' => 'Venue was unbookmarked.', 'action' => 'unbookmarked'];
                }

            } else {

                $sql = "INSERT INTO bookmarks (userId, venueId) VALUES (:userId, :venueId)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':venueId', $venueId);

                if ($stmt->execute()) {
                    $conn->commit();
                    return ['status' => 'success', 'message' => 'Venue has been bookmarked.', 'action' => 'bookmarked'];
                } else {
                    $conn->rollBack();
                    return ['status' => 'error', 'message' => 'Venue was not bookmarked.'];
                }
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error adding bookmark: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getBookmarks($userId = "")
    {
        try {
            $conn = $this->db->connect();

            $sql = "SELECT 
            v.id AS venue_id,
            v.*, 
            vss.name AS status, 
            vas.name AS availability, 
            GROUP_CONCAT(vi.image_url) AS image_urls,
            AVG(r.rating) AS rating
            FROM venues v 
            JOIN venue_status_sub vss ON v.status_id = vss.id 
            JOIN venue_availability_sub vas ON v.availability_id = vas.id 
            JOIN venue_images vi ON v.id = vi.venue_id
            JOIN bookmarks b ON b.venueId = v.id
            LEFT JOIN reviews r ON v.id = r.venue_id
            WHERE b.userId = :userId
            GROUP BY v.id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            $bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Convert image_urls to an array
            foreach ($bookmarks as &$venue) {
                if (!empty($venue['image_urls'])) {
                    $venue['image_urls'] = explode(',', $venue['image_urls']); // Convert image URLs to an array
                }
                // Check if the venue is bookmarked
                $venue['bookmarked'] = in_array($venue['venue_id'], $bookmarks);
            }

            return $bookmarks;

        } catch (PDOException $e) {
            error_log("Error fetching bookmarks: " . $e->getMessage());
            return [];
        }
    }

    public function giveReview($userId = null, $venueId = null, $review = null, $rating = null)
    {
        try {
            $conn = $this->db->connect();
            $sql = "INSERT INTO reviews (user_id, venue_id, rating, review) VALUES (:user_id, :venue_id, :rating, :review)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':venue_id', $venueId);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':review', $review);
            $stmt->execute();

            return ['status' => 'success', 'message' => 'Review added successfully'];
        } catch (PDOException $e) {
            error_log("Error adding review: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function updateUserInfo($userId, $firstname, $lastname, $middlename, $bio = null, $sex, $birthdate, $address, $email, $contact, $uploadedImages = null)
    {
        try {
            $conn = $this->db->connect();

            // Base SQL query
            $sql = "UPDATE users SET 
                    firstname = :firstname, 
                    lastname = :lastname, 
                    middlename = :middlename, 
                    sex_id = :sex, 
                    birthdate = :birthdate, 
                    address = :address, 
                    email = :email, 
                    contact_number = :contact";

            // Add `bio` to the query only if it's not null
            if (!is_null($bio)) {
                $sql .= ", bio = :bio";
            }

            // Add `profile_pic` to the query only if it's not null
            if (!is_null($uploadedImages)) {
                $sql .= ", profile_pic = :profile_pic";
            }

            $sql .= " WHERE id = :userId";

            $stmt = $conn->prepare($sql);

            // Bind required parameters
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':middlename', $middlename);
            $stmt->bindParam(':sex', $sex);
            $stmt->bindParam(':birthdate', $birthdate);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':contact', $contact);

            // Bind optional parameters if they are not null
            if (!is_null($bio)) {
                $stmt->bindParam(':bio', $bio);
            }
            if (!is_null($uploadedImages)) {
                $stmt->bindParam(':profile_pic', $uploadedImages);
            }

            $stmt->execute();

            return ['status' => 'success', 'message' => 'User information updated successfully'];
        } catch (PDOException $e) {
            error_log("Error updating user info: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function updateUserPassword($userId, $currentPass, $newPass, $confirmPass)
    {
        try {
            $conn = $this->db->connect();
            $sql = "SELECT password FROM users WHERE id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $user = $stmt->fetch();
            if (password_verify($currentPass, $user['password'])) {
                if (password_verify($newPass, $user['password'])) {
                    return ['status' => 'error', 'message' => 'New password must be different from the current password'];
                }
                if ($newPass == $confirmPass) {
                    $newPass = password_hash($newPass, PASSWORD_DEFAULT);
                    $updateSql = "UPDATE users SET password = :newPass WHERE id = :userId";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bindParam(':userId', $userId);
                    $updateStmt->bindParam(':newPass', $newPass);
                    $updateStmt->execute();
                    return ['status' => 'success', 'message' => 'Password updated successfully'];
                } else {
                    return ['status' => 'error', 'message' => 'New password and confirm password do not match'];
                }
            } else {
                return ['status' => 'error', 'message' => 'Current password is incorrect'];
            }
        } catch (PDOException $e) {
            error_log("Error updating user password: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function discountApplication($userId, $discount_type, $fullname, $discount_id, $card_image)
    {
        try {
            $conn = $this->db->connect();

            $checkSql = "SELECT * FROM mandatory_discount WHERE userId = :userId";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':userId', $userId);
            $checkStmt->execute();
            $check = $checkStmt->fetch();

            if ($check) {
                return ['status' => 'error', 'message' => 'You have already applied for a discount.'];
            } else {
                $sql = "INSERT INTO mandatory_discount (userId, discount_type, fullname, discount_id, card_image) VALUES (:userId, :discount_type, :fullname, :discount_id, :card_image)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':discount_type', $discount_type);
                $stmt->bindParam(':fullname', $fullname);
                $stmt->bindParam(':discount_id', $discount_id);
                $stmt->bindParam(':card_image', $card_image);

                if ($stmt->execute()) {
                    return ['status' => 'success', 'message' => 'Discount application sent successfully'];
                } else {
                    return ['status' => 'error', 'message' => 'Failed to send discount application'];
                }
            }

        } catch (PDOException $e) {
            error_log("Error sending discount application: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getDiscountApplication($userId)
    {
        try {
            $conn = $this->db->connect();
            // $ACTIVE_STATUS = "Active";

            $sql = "SELECT * FROM mandatory_discount WHERE userId = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            // $stmt->bindParam(':status', $ACTIVE_STATUS);
            $stmt->execute();
            $discount = $stmt->fetch();
            return $discount;
        } catch (PDOException $e) {
            error_log("Error fetching discount application: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    function getProfilePic($userId)
    {
        try {
            $sql = "SELECT profile_pic FROM users WHERE id = :userId";
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $profilePic = $stmt->fetchColumn();
            return $profilePic;
        } catch (PDOException $e) {
            error_log("Error fetching profile picture: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    // public function getUserById($userId)
    // {
    //     try {
    //         $sql = "SELECT firstname, lastname FROM users WHERE id = ?";
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute([$userId]);
    //         return $stmt->fetch();
    //     } catch (Exception $e) {
    //         error_log($e->getMessage());
    //         return false;
    //     }
    // }

    public function getDiscountApplications($search = "", $filter = "")
    {
        $sql = "SELECT 
                    md.id,
                    md.userId,
                    md.discount_type,
                    md.fullname as id_holder_name,
                    md.discount_id as id_number,
                    md.card_image as id_photo,
                    md.status,
                    CONCAT(u.firstname, ' ', u.lastname) as fullname,
                    u.email
                FROM mandatory_discount md
                JOIN users u ON md.userId = u.id
                WHERE 1=1";

        if (!empty($search)) {
            $sql .= " AND (md.fullname LIKE ? OR md.discount_id LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($filter)) {
            $sql .= " AND md.status = ?";
            $params[] = $filter;
        }

        $sql .= " ORDER BY md.created_at DESC";

        try {
            if (!empty($params)) {
                $stmt = $this->db->connect()->prepare($sql);
                $stmt->execute($params);
            } else {
                $stmt = $this->db->connect()->query($sql);
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function updateDiscountApplicationStatus($applicationId, $status)
    {
        try {
            $sql = "UPDATE mandatory_discount SET status = ? WHERE id = ?";
            $stmt = $this->db->connect()->prepare($sql);
            $newStatus = ($status === 'Approved') ? 'Active' : 'Inactive';
            $result = $stmt->execute([$newStatus, $applicationId]);
            if ($result) {
                return ['status' => 'success', 'message' => 'Action Successful'];
            } else {
                return ['status' => 'error', 'message' => 'Action Failed'];
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // public function getUserById($userId) {
    //     try {
    //         $sql = "SELECT firstname, lastname FROM users WHERE id = ?";
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute([$userId]);
    //         return $stmt->fetch();
    //     } catch (Exception $e) {
    //         error_log($e->getMessage());
    //         return false;
    //     }
    // }

}

// session_start();


$accountObj = new Account();


// var_dump($accountObj->getBookmarks(2));