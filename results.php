<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['answer'])) {
    echo '<script>window.location.href = "quiz.php";</script>';
    exit;
}

$answers = $_POST['answer'];
$score = 0;
$category_scores = ['Self-awareness' => 0, 'Empathy' => 0, 'Emotional regulation' => 0];
$category_max = ['Self-awareness' => 12, 'Empathy' => 12, 'Emotional regulation' => 16]; // Based on 3/3/4 questions *4
$category_counts = ['Self-awareness' => 3, 'Empathy' => 3, 'Emotional regulation' => 4];

foreach ($answers as $qid => $optid) {
    $stmt = $pdo->prepare("SELECT o.score, q.category FROM options o JOIN questions q ON o.question_id = q.id WHERE o.id = ? AND o.question_id = ?");
    $stmt->execute([$optid, $qid]);
    $res = $stmt->fetch();
    if ($res) {
        $score += $res['score'];
        $category_scores[$res['category']] += $res['score'];
    }
}

$max_score = 40; // 10 questions * 4
$percentage = ($score / $max_score) * 100;

// Personalized feedback
if ($percentage >= 80) {
    $feedback = "Outstanding EQ! You're highly skilled in managing emotions and understanding others.";
    $strengths = "Exceptional self-awareness, empathy, and emotional control.";
    $improvements = "Keep honing your skills for even greater leadership potential.";
} elseif ($percentage >= 60) {
    $feedback = "Solid EQ. You have a good grasp, with potential to excel further.";
    $strengths = "Balanced emotional insights across areas.";
    $improvements = "Focus on empathy in challenging social situations.";
} elseif ($percentage >= 40) {
    $feedback = "Moderate EQ. There's room to build stronger emotional habits.";
    $strengths = "Some natural talents in regulation or awareness.";
    $improvements = "Practice daily self-reflection and active listening.";
} else {
    $feedback = "Developing EQ. Start building foundations for better emotional health.";
    $strengths = "Everyone has potential – yours is waiting to be unlocked.";
    $improvements = "Begin with journaling emotions and seeking feedback from trusted friends.";
}

// Category-specific recommendations
$category_feedback = [];
foreach ($category_scores as $cat => $cat_score) {
    $cat_percent = ($cat_score / $category_max[$cat]) * 100;
    if ($cat_percent >= 80) {
        $category_feedback[$cat] = "Excellent in $cat – leverage this strength!";
    } elseif ($cat_percent >= 60) {
        $category_feedback[$cat] = "Good in $cat – consistent practice will enhance it.";
    } else {
        $category_feedback[$cat] = "Improve $cat by focusing on related exercises.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Results</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
            color: #333333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            animation: pulseBg 5s infinite alternate;
        }
        @keyframes pulseBg {
            0% { filter: brightness(1); }
            100% { filter: brightness(1.1); }
        }
        .results-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            animation: zoomIn 1.2s ease-out;
        }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        h1 {
            font-size: 2.8em;
            color: #28a745;
            margin-bottom: 20px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }
        p {
            font-size: 1.3em;
            margin: 10px 0;
            line-height: 1.5;
        }
        .category {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        .category:hover {
            transform: translateY(-3px);
        }
        button {
            background: linear-gradient(135deg, #ed213a 0%, #93291e 100%);
            border: none;
            color: #ffffff;
            padding: 15px 30px;
            margin: 15px 10px;
            font-size: 1.3em;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(237, 33, 58, 0.3);
        }
        button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(237, 33, 58, 0.5);
        }
        @media (max-width: 600px) {
            .results-container { padding: 20px; max-width: 90%; }
            h1 { font-size: 2em; }
            p { font-size: 1em; }
            button { padding: 10px 20px; font-size: 1em; margin: 10px 5px; }
        }
    </style>
</head>
<body>
    <div class="results-container">
        <h1>Your EQ Score: <?php echo $score; ?> / <?php echo $max_score; ?> (<?php echo round($percentage); ?>%)</h1>
        <p><?php echo $feedback; ?></p>
        <p><strong>Strengths:</strong> <?php echo $strengths; ?></p>
        <p><strong>Areas for Improvement:</strong> <?php echo $improvements; ?></p>
        <div class="category-feedback">
            <?php foreach ($category_feedback as $cat => $fb): ?>
                <div class="category">
                    <p><strong><?php echo $cat; ?> Score:</strong> <?php echo $category_scores[$cat]; ?> / <?php echo $category_max[$cat]; ?> - <?php echo $fb; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <button onclick="window.location.href='quiz.php'">Retake Test</button>
        <button onclick="shareResults()">Share Results</button>
    </div>
    <script>
        function shareResults() {
            const text = `My EQ Score: <?php echo $score; ?> / <?php echo $max_score; ?> (${<?php echo round($percentage); ?>}%)! Check yours too!`;
            if (navigator.share) {
                navigator.share({ title: 'My EQ Test Results', text: text });
            } else {
                alert(text);
            }
        }
    </script>
</body>
</html>
