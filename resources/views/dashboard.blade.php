<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Katalog Disarpus</title>
    <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 50px;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background: #343a40;
            color: #fff;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
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

    <div class="sidebar">
        <h3 class="text-center text-white">Dashboard</h3>
        <a href="{{ route('dashboard') }}">Home</a>
        <a href="{{ route('dashboard.login') }}">Log Out</a>
    </div>

    <div class="content">
        <div class="container">
			<h1 class="mb-5">Home</h1>
			<form method="POST" action="{{ route('dashboard') }}">
				@csrf
				<button type="submit" class="btn btn-primary">Sync</button>
			</form>
        </div>
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
