<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Homepage</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            animation: bgAnimation 10s infinite alternate;
        }
        @keyframes bgAnimation {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }
        .container {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            backdrop-filter: blur(10px);
            max-width: 700px;
            animation: fadeInUp 1.5s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h1 {
            font-size: 3em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: glow 2s infinite alternate;
        }
        @keyframes glow {
            from { text-shadow: 0 0 10px #fff; }
            to { text-shadow: 0 0 20px #fff; }
        }
        p {
            font-size: 1.3em;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        button {
            background: linear-gradient(135deg, #ff5e62 0%, #ff9966 100%);
            border: none;
            color: #ffffff;
            padding: 15px 40px;
            font-size: 1.5em;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(255, 94, 98, 0.5);
        }
        button:hover {
            transform: scale(1.1) rotate(2deg);
            box-shadow: 0 6px 20px rgba(255, 94, 98, 0.8);
        }
        @media (max-width: 600px) {
            .container { padding: 20px; max-width: 90%; }
            h1 { font-size: 2em; }
            p { font-size: 1em; }
            button { padding: 10px 30px; font-size: 1.2em; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Emotional Intelligence (EQ) Test</h1>
        <p>Emotional Intelligence is key to understanding yourself and others. It helps in managing stress, building relationships, and achieving success. Take this test to discover your EQ level and get personalized insights!</p>
        <button onclick="window.location.href='quiz.php'">Start Test</button>
    </div>
</body>
</html>
