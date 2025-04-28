<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ወደ መለያ ይግቡ</title>
    <style>
        .password-field {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-field i {
            position: absolute;
            right: 10px;
            cursor: pointer;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .registration-container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 150px;
            height: auto;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-group small {
            color: red;
            font-size: 12px;
            display: none;
        }

        .registration-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #ffffff;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .registration-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="registration-container">
        <div class="logo-container">
            <img src="/assets/images/logo/pplogo.jpg" alt="Logo">
        </div>
        <h2 style="text-align: center;">ወደ መለያ ይግቡ</h2>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form id="registrationForm" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="login">ኢሜይል ፣ ስልክ ቁጥር ወይም ዩዘን ኔም</label>
                    <input type="text" id="login" name="login" required>
                    @error('login')
                        <small>{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">የይልፍ ቃል</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" required>
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </div>
                    @error('password')
                        <small>{{ $message }}</small>
                    @enderror
                    <small>Password must be at least 6 characters</small>
                </div>
            </div>


            <button type="submit">ግባ</button>
        </form>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPassword = document.getElementById('password_confirmation');
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>