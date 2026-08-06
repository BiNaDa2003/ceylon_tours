<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Ceylon Tours</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0f7b6c;
            --primary-dark: #0a5c52;
            --accent: #f59e0b;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0a5c52 0%, #0f7b6c 50%, #1a9b8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-wrapper {
            width: 100%;
            max-width: 440px;
        }
        .login-card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 32px 64px rgba(0,0,0,.35);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            color: white;
        }
        .login-header .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
            border: 2px solid rgba(255,255,255,.25);
        }
        .login-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            margin-bottom: .3rem;
        }
        .login-header p {
            opacity: .8;
            font-size: .9rem;
            margin: 0;
        }
        .login-body {
            padding: 2rem;
            background: #fff;
        }
        .form-control {
            border-radius: .6rem;
            padding: .7rem 1rem;
            border: 1.5px solid #e5e7eb;
            transition: all .2s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15,123,108,.12);
        }
        .input-group-text {
            border-radius: .6rem 0 0 .6rem !important;
            border: 1.5px solid #e5e7eb;
            border-right: none;
        }
        .input-group .form-control {
            border-radius: 0 .6rem .6rem 0 !important;
        }
        .btn-login {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: .6rem;
            padding: .8rem;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .5px;
            color: white;
            width: 100%;
            transition: all .3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(15,123,108,.35);
            color: white;
        }
        .back-link {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: .85rem;
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            transition: color .2s;
        }
        .back-link:hover { color: var(--accent); }
        .form-label { font-weight: 600; font-size: .85rem; color: #374151; margin-bottom: .4rem; }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="card login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="icon-circle">
                <i class="fas fa-shield-alt text-white"></i>
            </div>
            <h2>Admin Portal</h2>
            <p>Ceylon Tours Management System</p>
        </div>

        <!-- Form Body -->
        <div class="login-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2 rounded-3 mb-3 small border-0">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?route=login" method="POST">
                <div class="mb-3">
                    <label class="form-label text-uppercase tracking-wide">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light" required placeholder="admin@example.com" autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label text-uppercase tracking-wide">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control bg-light" required placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login to Dashboard
                </button>
            </form>
        </div>
    </div>

    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>Back to Ceylon Tours Website
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
