<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Choose Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
        }

        .signup-container {
            max-width: 450px;
            margin: auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .signup-container h2 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .signup-box {
            border: 1px solid #e0e0e0;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
        }

        .signup-box:hover {
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .form-check-label {
            font-size: 16px;
            color: #333;
        }

        .btn-custom {
            background-color: #0066CC;
            border: none;
            color: white;
            font-size: 16px;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn-custom:hover {
            background-color: #005bb5;
        }

        .form-check {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="signup-container">
            <h2 class="text-center mb-4">Create an Account</h2>
            <form method="POST">
                <div class="signup-box">
                    <h4 class="mb-4">Select Your Role</h4>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="client" value="client" required>
                        <label class="form-check-label" for="client">
                            Client
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="freelancer" value="freelancer" required>
                        <label class="form-check-label" for="freelancer">
                            Freelancer
                        </label>
                    </div>

                    <!-- Dynamic Button Text -->
                    <button type="submit" class="btn-custom mt-4" id="joinButton">Join as Client</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update submit button text based on selected role
        const clientRadio = document.getElementById('client');
        const freelancerRadio = document.getElementById('freelancer');
        const joinButton = document.getElementById('joinButton');

        clientRadio.addEventListener('change', function() {
            if (this.checked) {
                joinButton.textContent = 'Join as Client';
            }
        });

        freelancerRadio.addEventListener('change', function() {
            if (this.checked) {
                joinButton.textContent = 'Join as Freelancer';
            }
        });
    </script>
</body>

</html>
