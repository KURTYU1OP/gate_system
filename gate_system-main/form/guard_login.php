<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Get all guards
    $guardData = json_decode($db->retrieve("Guard"), true);

    $found = false;
    $guardCampus = "";
    $guardName = "";
    $guardGate = "";

    if ($guardData) {
        foreach ($guardData as $key => $guard) {
            if (
                isset($guard['email']) &&
                isset($guard['password']) &&
                $guard['email'] === $email &&
                $guard['password'] === $password
            ) {
                $found = true;
                $guardCampus = $guard['campus'];
                $guardName = $guard['fullname'];
                $guardGate = $guard['gate_designation'];
                break;
            }
        }
    }

    if ($found) {
        $_SESSION['guard_logged_in'] = true;
        $_SESSION['guard_email'] = $email;
        $_SESSION['guard_campus'] = $guardCampus;
        $_SESSION['guard_name'] = $guardName;
        $_SESSION['guard_gate'] = $guardGate;

        header("Location: ../guard/guardMain.php");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Guard</title>

    <link rel="stylesheet" href="../assets/css/global.css">

    <style>
        body {
            background:
                linear-gradient(rgba(166, 2, 18, 0.35), rgba(166, 2, 18, 0.55)),
                url("../assets/images/Bulsu Pimental.png") no-repeat center center/cover;
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            overflow-x: hidden;
        }

        .particle {
            position: fixed;
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.35);
            border-radius: 50%;
            animation: floatParticles 12s linear infinite;
            z-index: -2;
            filter: blur(1px);
        }

        @keyframes floatParticles {
            0% {
                transform: translateY(0) scale(1);
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }

            100% {
                transform: translateY(-150vh) scale(1.6);
                opacity: 0;
            }
        }

        #login_card_container {
            width: 90%;
            max-width: 420px;
            margin: 50px auto;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.28);
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #header_card_section {
            background: rgba(166, 2, 18, 0.92);
            padding: 20px 10px;
            text-align: center;
        }

        #header_card_section img {
            width: 75px;
            margin-bottom: 10px;
            filter: drop-shadow(0px 3px 8px rgba(0, 0, 0, 0.3));
            filter: invert(100%) brightness(100%) saturate(0);
        }

        #user_role_title {
            color: white;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
        }

        #form_card_section {
            background: rgba(255, 255, 255, 0.96);
            padding: 35px 30px 45px 30px;
            backdrop-filter: blur(8px);
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #a60212;
            letter-spacing: 0.7px;
        }

        input {
            padding: 12px 14px;
            border-radius: 10px;
            border: 2px solid rgba(166, 2, 18, 0.35);
            font-size: 14px;
            outline: none;
            transition: 0.25s ease;
        }

        input:focus {
            border-color: #a60212;
            background: #fff9f9;
        }

        button {
            margin-top: 10px;
            padding: 13px 0;
            border: none;
            background: #a60212;
            color: white;
            font-weight: 600;
            border-radius: 12px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.25s ease;
            letter-spacing: 0.5px;
            box-shadow: 0px 4px 12px rgba(166, 2, 18, 0.35);
        }

        button:hover {
            background: #920111;
            transform: translateY(-2px);
            box-shadow: 0px 8px 20px rgba(166, 2, 18, 0.4);
        }

        a {
            margin-top: 5px;
            display: inline-block;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            color: #a60212;
            font-weight: 600;
            transition: 0.25s ease;
        }

        a:hover {
            color: #6b010b;
        }

        .error-message {
            color: #a60212;
            background: #ffe5e5;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid rgba(166, 2, 18, 0.2);
        }
    </style>
</head>

<body>
    <div id="login_card_container" class="container">
        <!-- RED -->
        <div id="header_card_section">
            <img src="../assets/images/icon guard.png" alt="icon guard">
            <p id="user_role_title">Welcome Guard!</p>
        </div>

        <!-- WHITE -->
        <div id="form_card_section">
            <form method="POST">
                <label for="username">EMAIL</label>
                <input type="text" id="username" name="username" autocomplete="off" required><br>

                <label for="password">PASSWORD</label>
                <input type="password" id="password" name="password" autocomplete="off" required><br>

                <button type="submit">Login</button>

                <?php if ($error): ?>
                    <p class="error-message"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </form>
            <a href="../index.php">Back</a>
        </div>
    </div>

    <script>
        // Generate floating particles
        for (let i = 0; i < 35; i++) {
            const particle = document.createElement("div");
            particle.className = "particle";
            particle.style.left = Math.random() * 100 + "vw";
            particle.style.top = Math.random() * 100 + "vh";
            particle.style.animationDuration = (10 + Math.random() * 10) + "s";
            particle.style.opacity = Math.random();
            document.body.appendChild(particle);
        }
    </script>
</body>

</html>