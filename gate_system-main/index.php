<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Student Gate Restiction System</title>

    <link rel="stylesheet" href="../assets/css/global.css?v=1">

    <style>
        body {
            background:
                linear-gradient(rgba(166, 2, 18, 0.35), rgba(166, 2, 18, 0.55)),
                url("assets/images/Bulsu Pimental.png") no-repeat center center/cover;
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
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

        #bulsu_logo_container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        #bulsu_logo_container img {
            width: 120px;
            filter: drop-shadow(0px 3px 8px rgba(0, 0, 0, 0.3));
        }

        #bulsu_title_container {
            text-align: center;
            margin-top: 0;
        }

        #bulsu_title_container h1 {
            color: white;
            font-size: 38px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-shadow: 0 3px 10px rgba(0, 0, 0, 0.45);
        }

        .user_role_container {
            width: 80%;
            max-width: 750px;
            margin: 10px auto 0 auto;
            background: rgba(255, 255, 255, 0.92);
            border-radius: 20px;
            padding: 20px 20px;
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
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

        #user_role_title {
            font-size: 26px;
            font-weight: 700;
            color: #a60212;
            margin-bottom: 20px;
            text-align: center;
            letter-spacing: 0.3px;
        }

        .user_card_container {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .user_card_container div {
            width: 150px;
            height: 150px;
            background: white;
            border: 2px solid rgba(166, 2, 18, 0.4);
            border-radius: 15px;
            cursor: pointer;
            transition: 0.35s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            box-shadow:
                0px 6px 16px rgba(0, 0, 0, 0.18),
                0px 4px 12px rgba(0, 0, 0, 0.12),
                0px 2px 6px rgba(0, 0, 0, 0.10);
        }

        .user_card_container div:hover {
            transform: translateY(-6px) scale(1.03);
            border: 2.5px solid #a60212;
            background: #fff9f9;

            box-shadow:
                0px 10px 25px rgba(166, 2, 18, 0.25),
                0px 6px 15px rgba(0, 0, 0, 0.18);
        }

        .user_card_container img {
            height: 90px;
            margin-bottom: 8px;
            transition: 0.3s ease;
        }

        .user_card_container div:hover img {
            transform: scale(1.05);
        }

        .user_card_container div p {
            font-weight: 600;
            color: #a60212;
            letter-spacing: 0.5px;
        }

        #user_role_description {
            color: #333;
            font-size: 14px;
            margin-top: 20px;
            line-height: 1.5;
            text-align: center;
            font-weight: 400;
        }

        .role_link {
            text-decoration: none;
            color: inherit;
        }
    </style>

</head>

<body>
    <div id="bulsu_logo_container" class="container">
        <img src="assets/images/Bulsu LOGO.png" alt="BulSU Logo">
    </div>

    <div id="bulsu_title_container" class="container">
        <h1>BULACAN STATE UNIVERSITY</h1>
    </div>

    <div class="user_role_container">
        <p id="user_role_title">Select your campus role</p>

        <div class="user_card_container">

            <a href="form/admin_login.php" class="role_link">
                <div id="admin_card">
                    <img src="assets/images/icon admin.png" alt="admin icon">
                    <p>Admin</p>
                </div>
            </a>

            <a href="form/staff_login.php" class="role_link">
                <div id="staff_card">
                    <img src="assets/images/icon staff.png" alt="staff icon">
                    <p>Staff</p>
                </div>
            </a>

            <a href="form/guard_login.php" class="role_link">
                <div id="guard_card">
                    <img src="assets/images/icon guard.png" alt="guard icon">
                    <p>Guard</p>
                </div>
            </a>

        </div>

        <p id="user_role_description">
            An access control system that regulates student entry and exit based on class schedules,
            using web and mobile platforms to improve security and streamline gate operations.
        </p>
    </div>

    <script>
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