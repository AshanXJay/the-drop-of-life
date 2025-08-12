<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Need Blood - Request Form</title>
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
        
        .blood-request-form {
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
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .required::after {
            content: " *";
            color: #c0392b;
        }
        
        select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        select:focus, textarea:focus {
            border-color: #c0392b;
            outline: none;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .search-btn {
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
        
        .search-btn:hover {
            background-color: #a93226;
        }
        
        @media (max-width: 600px) {
            .blood-request-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="blood-request-form">
        <h1>Need Blood</h1>
        
        <form action="#" method="POST">
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
                <label for="reason" class="required">Reason, why do you need blood?</label>
                <textarea id="reason" name="reason" required></textarea>
            </div>
            
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>
</body>
</html>
<?php include 'includes/footer.php'; ?>
