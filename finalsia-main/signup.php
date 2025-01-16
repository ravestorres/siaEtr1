<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Choose Role</title>
    <link rel="stylesheet" href="css/signup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
         
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="signup-container">
            <h2 class="text-center mb-4">Create an Account</h2>
            <form method="POST">
                <div class="signup-box">
                    <h4>Select Your Role</h4>
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
                    <button type="submit" class="btn btn-primary mt-4 w-100" id="joinButton">Join as Client</button>

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