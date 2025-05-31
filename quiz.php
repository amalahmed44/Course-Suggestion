<?php
session_start();

$host = 'localhost';
$db = 'course_suggestion';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('DB Connection error: ' . $mysqli->connect_error);
}

// Pagination & answers saving
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$questionsPerPage = 3;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['answers'] ?? [] as $qid => $aid) {
        $_SESSION['answers'][$qid] = $aid;
    }
    $nextPage = $page + 1;
    header("Location: quiz.php?page=$nextPage");
    exit;
}

// Total questions count
$result = $mysqli->query("SELECT COUNT(*) as total FROM questions");
$totalQuestions = $result->fetch_assoc()['total'];
$totalPages = ceil($totalQuestions / $questionsPerPage);

// Fetch questions for current page
$offset = ($page - 1) * $questionsPerPage;
$stmt = $mysqli->prepare("SELECT id, question_text FROM questions ORDER BY id LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $questionsPerPage, $offset);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch answers for these questions
$questionIds = array_column($questions, 'id');
if (count($questionIds) > 0) {
    $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
    $types = str_repeat('i', count($questionIds));
    $stmt2 = $mysqli->prepare("SELECT id, question_id, answer_text FROM answers WHERE question_id IN ($placeholders) ORDER BY question_id, id");
    $stmt2->bind_param($types, ...$questionIds);
    $stmt2->execute();
    $answersResult = $stmt2->get_result();

    $answers = [];
    while ($row = $answersResult->fetch_assoc()) {
        $answers[$row['question_id']][] = $row;
    }
} else {
    $answers = [];
}

// Calculate progress percent
$progressPercent = intval(($page - 1) / $totalPages * 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Course Suggestion Quiz</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

  body {
    font-family: 'Montserrat', sans-serif;
    background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
    color: #222;
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 40px 20px;
  }

  .container {
    background: white;
    border-radius: 16px;
    max-width: 720px;
    width: 100%;
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    padding: 40px 50px;
  }

  h1 {
    text-align: center;
    color: #3a86ff;
    margin-bottom: 25px;
    font-weight: 700;
    font-size: 2.8rem;
  }

  .progress-bar {
    height: 10px;
    background: #eee;
    border-radius: 8px;
    margin-bottom: 40px;
    overflow: hidden;
  }

  .progress-bar-fill {
    height: 100%;
    width: <?= $progressPercent ?>%;
    background: #3a86ff;
    transition: width 0.4s ease-in-out;
  }

  .question-card {
    background: #f0f4f8;
    border-radius: 12px;
    padding: 20px 25px;
    margin-bottom: 28px;
    box-shadow: 0 6px 15px rgba(58, 134, 255, 0.2);
  }

  .question-card strong {
    display: block;
    font-size: 1.2rem;
    margin-bottom: 16px;
    color: #0b3d91;
  }

  .answers label {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 8px;
    padding: 14px 20px;
    margin-bottom: 12px;
    cursor: pointer;
    font-weight: 500;
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    transition: background-color 0.25s ease;
  }
  .answers label:hover {
    background-color: #d0e2ff;
  }
  input[type="radio"] {
    margin-right: 18px;
    accent-color: #3a86ff;
    width: 18px;
    height: 18px;
  }

  .nav-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 36px;
  }

  button {
    background: #3a86ff;
    color: white;
    border: none;
    padding: 14px 30px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(58, 134, 255, 0.4);
    transition: background-color 0.3s ease;
  }
  button:disabled {
    background: #a9c5ff;
    cursor: not-allowed;
    box-shadow: none;
  }
  button:hover:not(:disabled) {
    background: #2e65cc;
  }

  a {
    text-decoration: none;
  }
</style>
</head>
<body>
  <div class="container">
    <h1>Course Suggestion Quiz</h1>

    <div class="progress-bar" aria-label="Quiz progress">
      <div class="progress-bar-fill"></div>
    </div>

    <form method="POST" action="quiz.php?page=<?= $page ?>">
      <?php foreach ($questions as $q): ?>
        <div class="question-card">
          <strong><?= $q['id'] ?>. <?= htmlspecialchars($q['question_text']) ?></strong>
          <div class="answers">
            <?php foreach ($answers[$q['id']] ?? [] as $a):
              $checked = (isset($_SESSION['answers'][$q['id']]) && $_SESSION['answers'][$q['id']] == $a['id']) ? 'checked' : '';
            ?>
              <label>
                <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $a['id'] ?>" required <?= $checked ?>>
                <?= htmlspecialchars($a['answer_text']) ?>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="nav-buttons">
        <?php if ($page > 1): ?>
          <a href="quiz.php?page=<?= $page - 1 ?>"><button type="button">⬅️ Previous</button></a>
        <?php else: ?>
          <button type="button" disabled>⬅️ Previous</button>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
          <button type="submit">Next ➡️</button>
        <?php else: ?>
          <button type="submit" formaction="result.php">Finish ✅</button>
        <?php endif; ?>
      </div>
    </form>
  </div>
</body>
</html>

<?php $mysqli->close(); ?>
