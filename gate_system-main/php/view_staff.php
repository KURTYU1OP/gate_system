<?php
include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

// Get the staff key
$key = $_GET['id'] ?? '';

$data = $db->retrieve("Staff/$key");
$staff = json_decode($data, true);

if (!$staff) {
    echo "<script>alert('Staff not found!'); window.history.back();</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Staff</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background:
                linear-gradient(rgba(166, 2, 18, 0.35), rgba(166, 2, 18, 0.55)),
                url("../assets/images/Bulsu Pimental.png") no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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

        .id_card {
            width: 350px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border-top: 6px solid #a60212;
            padding: 20px;
        }

        .id_card h2 {
            text-align: center;
            color: #a60212;
            margin: 0 0 20px 0;
            font-size: 22px;
            letter-spacing: 1px;
        }

        .field {
            margin-bottom: 12px;
        }

        .label {
            font-weight: 700;
            font-size: 14px;
            color: black;
        }

        .value {
            font-size: 15px;
            color: #222;
            margin-top: 3px;
        }

        .back_btn {
            display: block;
            width: fit-content;
            margin: 20px auto 0;
            padding: 8px 15px;
            background: #a60212;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: 0.2s;
        }

        .back_btn:hover {
            background: #8a010f;
        }
    </style>
</head>

<body>
    <div class="id_card">
        <h2>STAFF INFO</h2>

        <div class="field">
            <div class="label">Full Name</div>
            <div class="value"><?= htmlspecialchars($staff['firstname'] . ' ' . $staff['middlename'] . ' ' . $staff['lastname']) ?></div>
        </div>

        <div class="field">
            <div class="label">Email</div>
            <div class="value"><?= htmlspecialchars($staff['email']) ?></div>
        </div>

        <div class="field">
            <div class="label">College</div>
            <div class="value"><?= htmlspecialchars($staff['college']) ?></div>
        </div>

        <div class="field">
            <div class="label">Campus</div>
            <div class="value"><?= htmlspecialchars($staff['campus']) ?></div>
        </div>

        <div class="field">
            <div class="label">Password</div>
            <div class="value"><?= htmlspecialchars($staff['password']) ?></div>
        </div>

        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
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