
<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Registration</title>
    <style>
        /* CSS Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .donation-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #c0392b;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            border-color: #c0392b;
            outline: none;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .required::after {
            content: " *";
            color: #c0392b;
        }
        
        .submit-btn {
            background-color: #c0392b;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 30px auto 0;
            width: 150px;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #a93226;
        }
        
        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .donation-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="donation-form">
        <h1>Donate Blood</h1>
        
        <form action="#" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="fullname" class="required">Full Name</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                
                <div class="form-group">
                    <label for="mobile" class="required">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email" class="required">Email id</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="age" class="required">Age</label>
                    <input type="number" id="age" name="age" min="18" max="65" required>
                </div>
                
                <div class="form-group">
                    <label for="gender" class="required">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="blood-group" class="required">Blood Group</label>
                <select id="blood-group" name="blood-group" required>
                    <option value="">Select</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="address" class="required">Address</label>
                <textarea id="address" name="address" rows="3" required></textarea>
            </div>
            
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</body>
</html>
<?php include 'includes/footer.php'; ?>
