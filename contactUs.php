<?php
require_once 'config.php';
require_once 'components/navbar.php';

// Handle form submission
$name = $email = $message = '';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Here you can add code to save the message to the database or send an email
        $success = "Thank you for contacting us, $name! We appreciate your feedback.";
        $name = $email = $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Placement Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .contact-section {
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            padding: 60px 0 40px 0;
        }

        .contact-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a237e;
            letter-spacing: 2px;
        }

        .contact-content {
            font-size: 1.15rem;
            color: #495057;
            max-width: 700px;
            margin: 0 auto;
        }

        .contact-card {
            border: none;
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(44, 62, 80, 0.10);
            padding: 2.5rem 2rem 2rem 2rem;
            background: #fff;
            max-width: 500px;
            margin: 0 auto;
        }

        .contact-info-icon {
            font-size: 2rem;
            color: #4f8cff;
            background: #e3f0ff;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            box-shadow: 0 2px 12px rgba(79, 140, 255, 0.08);
        }

        .contact-info-label {
            font-weight: 600;
            color: #1a237e;
        }

        .contact-social a {
            margin: 0 8px;
            font-size: 1.7rem;
        }

        @media (max-width: 767px) {
            .contact-title {
                font-size: 2rem;
            }

            .contact-content {
                font-size: 1rem;
            }

            .contact-card {
                padding: 1.2rem 0.5rem;
            }
        }
    </style>
</head>

<body>
    <section class="contact-section">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="contact-title">Contact Us</h1>
                <p class="contact-content mt-3">
                    Have a question, suggestion, or need support? Fill out the form below and our Placement Portal team
                    will get back to you soon!
                </p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="contact-card">
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php elseif ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="post" autocomplete="off" class="mb-4">
                            <div class="mb-3">
                                <label for="name" class="form-label contact-info-label"><i class="bi bi-person"></i>
                                    Your Name</label>
                                <input type="text" class="form-control" id="name" name="name" maxlength="50"
                                    value="<?php echo htmlspecialchars($name); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label contact-info-label"><i class="bi bi-envelope"></i>
                                    Your Email</label>
                                <input type="email" class="form-control" id="email" name="email" maxlength="60"
                                    value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label contact-info-label"><i
                                        class="bi bi-chat-left-text"></i> Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" maxlength="1000"
                                    required><?php echo htmlspecialchars($message); ?></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send"></i> Send
                                    Message</button>
                            </div>
                        </form>
                        <hr>
                        <div class="row text-center">
                            <div class="col-12 col-md-4 mb-3 mb-md-0">
                                <div class="contact-info-icon"><i class="bi bi-envelope"></i></div>
                                <div><a href="mailto:info@placementportal.com"
                                        class="text-decoration-none text-dark">info@placementportal.com</a></div>
                            </div>
                            <div class="col-12 col-md-4 mb-3 mb-md-0">
                                <div class="contact-info-icon"><i class="bi bi-telephone"></i></div>
                                <div>+91 12345 67890</div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="contact-info-icon"><i class="bi bi-geo-alt"></i></div>
                                <div>Placement Cell, Your Institute, City</div>
                            </div>
                        </div>
                        <div class="contact-social text-center mt-4">
                            <a href="https://www.linkedin.com/" target="_blank" class="text-primary"><i
                                    class="bi bi-linkedin"></i></a>
                            <a href="https://twitter.com/" target="_blank" class="text-info"><i
                                    class="bi bi-twitter"></i></a>
                            <a href="https://facebook.com/" target="_blank" class="text-primary"><i
                                    class="bi bi-facebook"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>