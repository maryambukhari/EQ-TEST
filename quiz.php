<?php
include 'db.php';
$stmt = $pdo->query("SELECT * FROM questions ORDER BY id");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$questions_with_options = [];
foreach ($questions as $q) {
    $opt_stmt = $pdo->prepare("SELECT * FROM options WHERE question_id = ? ORDER BY score DESC");
    $opt_stmt->execute([$q['id']]);
    $options = $opt_stmt->fetchAll(PDO::FETCH_ASSOC);
    $questions_with_options[] = ['question' => $q, 'options' => $options];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Quiz</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: #333333;
            margin: 0;
            padding: 20px;
            animation: bgFade 8s infinite alternate;
        }
        @keyframes bgFade {
            0% { opacity: 0.8; }
            100% { opacity: 1; }
        }
        .quiz-container {
            max-width: 800px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideInLeft 1s ease-out;
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-100px); }
            to { opacity: 1; transform: translateX(0); }
        }
        h2 {
            text-align: center;
            color: #007bff;
            font-size: 2.5em;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        .question {
            display: none;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
            margin-bottom: 20px;
            transition: all 0.5s ease;
        }
        .question.active {
            display: block;
            animation: fadeInQuestion 0.8s ease;
        }
        @keyframes fadeInQuestion {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        p {
            font-size: 1.4em;
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin: 10px 0;
            padding: 15px;
            background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        label:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #e0e0e0 0%, #d0d0d0 100%);
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        button {
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
            border: none;
            color: #ffffff;
            padding: 12px 25px;
            font-size: 1.2em;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(33, 147, 176, 0.3);
        }
        button:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 20px rgba(33, 147, 176, 0.5);
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        @media (max-width: 600px) {
            .quiz-container { padding: 15px; }
            h2 { font-size: 1.8em; }
            p { font-size: 1.1em; }
            label { padding: 10px; }
            button { padding: 10px 20px; font-size: 1em; }
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h2>Emotional Intelligence Quiz</h2>
        <form id="quiz-form" method="post" action="results.php">
            <?php foreach ($questions_with_options as $index => $qwo): ?>
                <div class="question <?php if ($index == 0) echo 'active'; ?>" id="question-<?php echo $index; ?>">
                    <p><?php echo ($index + 1) . '. ' . htmlspecialchars($qwo['question']['question']); ?> (Category: <?php echo htmlspecialchars($qwo['question']['category']); ?>)</p>
                    <?php foreach ($qwo['options'] as $opt): ?>
                        <label>
                            <input type="radio" name="answer[<?php echo $qwo['question']['id']; ?>]" value="<?php echo $opt['id']; ?>">
                            <?php echo htmlspecialchars($opt['option_text']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <div class="navigation">
                <button type="button" id="prev" disabled>Previous</button>
                <button type="button" id="next">Next</button>
                <button type="submit" id="submit" style="display:none;">Submit</button>
            </div>
        </form>
    </div>
    <script>
        const questions = document.querySelectorAll('.question');
        let current = 0;
        const prevBtn = document.getElementById('prev');
        const nextBtn = document.getElementById('next');
        const submitBtn = document.getElementById('submit');

        function showQuestion(index) {
            questions.forEach(q => q.classList.remove('active'));
            questions[index].classList.add('active');
            prevBtn.disabled = index === 0;
            if (index === questions.length - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                submitBtn.style.display = 'none';
            }
        }

        prevBtn.onclick = () => {
            if (current > 0) {
                current--;
                showQuestion(current);
            }
        };

        nextBtn.onclick = () => {
            if (current < questions.length - 1) {
                const radios = questions[current].querySelectorAll('input[type="radio"]');
                let answered = Array.from(radios).some(r => r.checked);
                if (!answered) {
                    alert('Please select an answer before proceeding!');
                    return;
                }
                current++;
                showQuestion(current);
            }
        };
    </script>
</body>
</html>
