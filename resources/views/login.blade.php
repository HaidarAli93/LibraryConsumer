<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            border-color: #007BFF;
            outline: none;
        }
        .error-messages {
            color: #ff0000;
            font-size: 12px;
            margin-top: 10px;
        }
        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: royalblue;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .register-link a {
            text-decoration: none;
            color: #007BFF;
        }
        .register-link git a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
	@if (session('toast'))
		<div class="toast-container position-fixed top-0 end-0 p-3">
			<div id="syncToast"
				 class="toast fade text-bg-{{ session('toast.type') }}"
				 role="alert"
				 aria-live="assertive"
				 aria-atomic="true">

				<div class="d-flex">
					<div class="toast-body">
						{{ session('toast.message') }}
					</div>

					<button type="button"
							class="btn-close btn-close-white me-2 m-auto"
							data-bs-dismiss="toast"
							aria-label="Close">
					</button>
				</div>

			</div>
		</div>
	@endif

    <div class="login-container">
        <h2>Login</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            @if ($errors->any())
                <div class="error-messages">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <button type="submit" class="submit-button">Sign in</button>
        </form>
    </div>

    <!-- Bootstrap JS & jQuery -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

	@if(session('toast'))
		<script>
		document.addEventListener('DOMContentLoaded', function () {
			const toastElement = document.getElementById('syncToast');

			const toast = new bootstrap.Toast(toastElement, {
				autohide: true,
				delay: 3000
			});

			toast.show();
		});
		</script>
	@endif
</body>
</html>
