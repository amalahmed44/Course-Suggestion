<?php
session_start();

// Your DB connection here
$host = 'localhost';
$db = 'course_suggestion';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die('DB Connection error: ' . $mysqli->connect_error);
}

// Make sure session has answers
if (empty($_SESSION['answers'])) {
    header('Location: quiz.php');
    exit;
}

// Prepare placeholders for selected answers
$selectedAnswerIds = array_values($_SESSION['answers']);
$placeholders = implode(',', array_fill(0, count($selectedAnswerIds), '?'));
$types = str_repeat('i', count($selectedAnswerIds));

$stmt = $mysqli->prepare("SELECT score, category FROM answers WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$selectedAnswerIds);
$stmt->execute();
$result = $stmt->get_result();

$categoryScores = [];
while ($row = $result->fetch_assoc()) {
    $cat = $row['category'];
    $score = (int)$row['score'];
    if (!isset($categoryScores[$cat])) {
        $categoryScores[$cat] = 0;
    }
    $categoryScores[$cat] += $score;
}

arsort($categoryScores);

$courseSuggestions = [
    'logic' => ['Computer Science', 'Mathematics', 'Philosophy'],
    'creativity' => ['Graphic Design', 'Fine Arts', 'Advertising'],
    'engineering' => ['Mechanical Engineering', 'Electrical Engineering', 'Robotics'],
    'innovation' => ['Entrepreneurship', 'Product Design', 'Research & Development'],
    'language' => ['Linguistics', 'Journalism', 'Creative Writing'],
    'math' => ['Statistics', 'Actuarial Science', 'Economics'],
    'social' => ['Psychology', 'Social Work', 'Education'],
    'science' => ['Biology', 'Physics', 'Astronomy'],
    'performance' => ['Drama', 'Music', 'Dance'],
    'tech' => ['Information Technology', 'Cybersecurity', 'Network Administration'],
    'management' => ['Business Administration', 'Event Management', 'Marketing'],
    'teaching' => ['Education', 'Special Education', 'Curriculum Development'],
];

// Take top 3 categories safely
$topCategories = count($categoryScores) ? array_slice($categoryScores, 0, 3, true) : [];

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Your Course Suggestions 🎓</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

  body {
    margin: 0; padding: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    color: #f0f0f0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .container {
    background: #1e2e3e;
    max-width: 720px;
    padding: 40px 50px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    text-align: center;
  }

  h1 {
    font-size: 2.8rem;
    margin-bottom: 15px;
    color: #ff6f61;
    text-shadow: 0 0 10px #ff6f61aa;
  }

  p {
    font-size: 1.15rem;
    margin-bottom: 35px;
    color: #ccc;
  }

  .category-card {
    background: #2a3a4c;
    margin: 20px 0;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.4);
    transition: transform 0.3s ease;
  }

  .category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(255,111,97,0.6);
  }

  .category-title {
    font-size: 1.8rem;
    margin-bottom: 10px;
    color: #ff6f61;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
  }

  .category-title svg {
    width: 32px;
    height: 32px;
    fill: #ff6f61;
    animation: pulse 2.5s infinite;
  }

  ul {
    list-style: none;
    padding-left: 0;
    color: #ddd;
    font-size: 1.1rem;
  }

  ul li {
    background: #ff6f6155;
    margin: 8px 0;
    padding: 10px 15px;
    border-radius: 10px;
    text-align: left;
    box-shadow: inset 0 0 5px #ff6f6188;
  }

  .score {
    font-weight: 600;
    font-size: 1rem;
    color: #ffa08c;
    margin-top: 6px;
  }

  .restart {
    margin-top: 40px;
  }

  button {
    background: #ff6f61;
    border: none;
    color: white;
    padding: 15px 40px;
    font-size: 1.2rem;
    border-radius: 30px;
    cursor: pointer;
    transition: background 0.3s ease;
    box-shadow: 0 5px 15px #ff6f61aa;
  }

  button:hover {
    background: #ff4a3d;
  }

  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
  }

  /* Responsive */
  @media (max-width: 768px) {
    .container {
      padding: 30px 20px;
      max-width: 90vw;
    }

    h1 {
      font-size: 2rem;
    }

    .category-title {
      font-size: 1.4rem;
    }
  }
</style>
</head>
<body>

<div class="container">
  <h1>🎓 Your Top Course Matches!</h1>
  <p>Based on your answers, these fields suit you best:</p>

  <?php foreach ($topCategories as $category => $score): ?>
    <div class="category-card" aria-label="Category <?= htmlspecialchars($category) ?>">
      <div class="category-title">
        <?= ucfirst($category) ?>
        <!-- Fun pulse icon -->
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2L15 8l6 1-4.5 4 1 6-5-3-5 3 1-6L3 9l6-1z"/></svg>
      </div>
      <div class="score">Score: <?= $score ?></div>
      <ul>
        <?php
          $courses = $courseSuggestions[$category] ?? ['Explore various courses!'];
          foreach ($courses as $course) {
              echo "<li>" . htmlspecialchars($course) . "</li>";
          }
        ?>
      </ul>
    </div>
  <?php endforeach; ?>

  <div class="restart">
    <form action="quiz.php" method="get">
      <button type="submit" aria-label="Restart Quiz">🔄 Take the Quiz Again</button>
    </form>
  </div>
</div>

</body>
</html>
