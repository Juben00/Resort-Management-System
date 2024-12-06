<?php
require_once '../classes/account.class.php';
require_once '../sanitize.php';

$accountObj = new Account();

$firstname = $lastname = $middlename = $sex = $birthdate = $contact_number = $address = $email = $password = $user_type = '';
$firstnameErr = $lastnameErr = $middlenameErr = $sexErr = $birthdateErr = $contact_numberErr = $addressErr = $emailErr = $passwordErr = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = clean_input($_POST['firstname']);
    $lastname = clean_input($_POST['lastname']);
    $middlename = clean_input($_POST['middlename']);
    $sex = clean_input($_POST['sex']);
    $birthdate = clean_input($_POST['birthdate']);
    $contact_number = clean_input($_POST['contact']);
    $address = clean_input($_POST['signupaddress']);
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);
    $user_type = clean_input(isset($_POST['userRole']) ? $_POST['userRole'] : '2');

    if (empty($firstname)) {
        $firstnameErr = 'First name is required';
    } else if (!preg_match('/^[a-zA-Z\s]+$/', $firstname)) {
        $firstnameErr = 'First name must contain letters and spaces only';
    }

    if (empty($lastname)) {
        $lastnameErr = 'Last name is required';
    } else if (!preg_match('/^[a-zA-Z\s]+$/', $lastname)) {
        $lastnameErr = 'Last name must contain letters and spaces only';
    }

    if (empty($middlename)) {
        $middlenameErr = 'Middle name is required';
    }

    if (empty($sex)) {
        $sexErr = "sex is required";
    }

    if (empty($birthdate)) {
        $birthdateErr = 'Birthdate is required';
    }

    if (empty($contact_number)) {
        $contact_numberErr = 'Contact number is required';
    } else if (!preg_match('/^[0-9]+$/', $contact_number)) {
        $contact_numberErr = 'Contact number must contain numbers only';
    }

    if (empty($address)) {
        $addressErr = 'Address is required';
    }

    if (empty($email)) {
        $emailErr = 'Email is required';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = 'Invalid email format';
    }

    if (empty($password)) {
        $passwordErr = 'Password is required';
    } else if (strlen($password) < 8) {
        $passwordErr = 'Password must be at least 8 characters';
    } else if (!preg_match('/[A-Z]/', $password)) {
        $passwordErr = 'Password must contain at least one uppercase letter';
    } else if (!preg_match('/[a-z]/', $password)) {
        $passwordErr = 'Password must contain at least one lowercase letter';
    } else if (!preg_match('/[0-9]/', $password)) {
        $passwordErr = 'Password must contain at least one number';
    }

    if (empty($user_type)) {
        $user_type = '2';
    }

    if (empty($firstnameErr) && empty($lastnameErr) && empty($middlenameErr) && empty($sexErr) && empty($birthdateErr) && empty($contact_numberErr) && empty($addressErr) && empty($emailErr) && empty($passwordErr)) {
        $accountObj->firstname = $firstname;
        $accountObj->lastname = $lastname;
        $accountObj->middlename = $middlename;
        $accountObj->sex = $sex;
        $accountObj->birthdate = $birthdate;
        $accountObj->contact_number = $contact_number;
        $accountObj->address = $address;
        $accountObj->email = $email;
        $accountObj->password = $password;
        $accountObj->usertype = $user_type;

        $result = $accountObj->signup();
        if ($result['status'] == 'success') {
            echo json_encode([
                'status' => 'success',
                'message' => $result['message']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $result['message']
            ]);
        }

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => implode('<br>', array_filter([
                $firstnameErr,
                $lastnameErr,
                $middlenameErr,
                $sexErr,
                $birthdateErr,
                $contact_numberErr,
                $addressErr,
                $emailErr,
                $passwordErr
            ]))
        ]);
        exit();
    }
}