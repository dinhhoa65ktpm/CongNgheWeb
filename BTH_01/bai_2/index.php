<?php
$quiz_file = __DIR__ . '/Quiz.txt';
if (!file_exists($quiz_file)) {
    die("Không tìm thấy tệp Quiz.txt. Vui lòng đặt Quiz.txt vào cùng thư mục với quiz.php");
}
// Hàm đơn giản escape HTML
function h($s) { return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

// Phân tích file thành mảng câu hỏi
$raw = file_get_contents($quiz_file);
$blocks = preg_split("/\R{2,}/u", trim($raw)); // tách bằng 1 hoặc nhiều dòng trống

$questions = [];
foreach ($blocks as $block) {
    $lines = preg_split("/\R/u", trim($block));
    if (count($lines) === 0) continue;

    // Thông thường: dòng 0 = câu hỏi, dòng tiếp là các lựa chọn A./B./..., cuối cùng là ANSWER: ...
    $questionText = array_shift($lines);
    $options = [];
    $answerLine = '';
    foreach ($lines as $ln) {
        $ln = trim($ln);
        if (stripos($ln, 'ANSWER:') === 0) {
            $answerLine = trim(substr($ln, 7));
        } elseif (preg_match('/^[A-Z]\.\s*(.*)$/u', $ln, $m)) {
            $letter = strtoupper($ln[0]);
            $text = trim($m[1]);
            $options[$letter] = $text;
        } else {
            // nếu có dòng text lẻ (ví dụ mô tả dài), gộp vào câu hỏi
            $questionText .= ' ' . $ln;
        }
    }

    // Chuẩn hóa đáp án: dạng 'C' hoặc 'C, D' hoặc 'C,D'
    $answers = [];
    if ($answerLine !== '') {
        // tách theo dấu phẩy hoặc khoảng trắng có dấu phẩy
        $parts = preg_split('/[,\s]+/', trim($answerLine));
        foreach ($parts as $p) {
            $p = trim($p);
            if ($p === '') continue;
            // chỉ lấy chữ cái đầu nếu người ta ghi "C," or "C,D"
            $letter = strtoupper($p[0]);
            if (ctype_alpha($letter)) $answers[] = $letter;
        }
    }

    $questions[] = [
        'q' => $questionText,
        'options' => $options,
        'answers' => array_values(array_unique($answers)), // mảng chữ cái
    ];
}

// Xử lý submit chấm điểm
$results = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_answers = $_POST['ans'] ?? []; // ans[0]=... but keys will be indices (string)
    $total_score = 0.0;
    $max_score = count($questions);
    $per_question_scores = [];

    foreach ($questions as $i => $ques) {
        $correct = $ques['answers']; // array of letters
        $is_multi = count($correct) > 1;
        $selected = [];
        if (isset($user_answers[$i])) {
            // For checkboxes radio: could be string or array
            if (is_array($user_answers[$i])) {
                $selected = array_map('strtoupper', $user_answers[$i]);
            } else {
                $selected = [strtoupper($user_answers[$i])];
            }
        }

        $score = 0.0;
        if (!$is_multi) {
            $score = (count($selected) === 1 && $selected[0] === $correct[0]) ? 1.0 : 0.0;
        } else {
            $correct_set = array_map('strtoupper', $correct);
            $sel_unique = array_values(array_unique($selected));
            $n_true_selected = 0;
            $n_false_selected = 0;
            foreach ($sel_unique as $s) {
                if (in_array($s, $correct_set)) $n_true_selected++;
                else $n_false_selected++;
            }
            $score_raw = ($n_true_selected - $n_false_selected) / max(1, count($correct_set));
            if ($score_raw < 0) $score_raw = 0;
            if ($score_raw > 1) $score_raw = 1;
            $score = $score_raw;
        }
        $per_question_scores[$i] = [
            'score' => $score,
            'selected' => $selected,
            'correct' => $correct
        ];
        $total_score += $score;
    }

    $percent = round(100 * $total_score / max(1, $max_score), 2);
    $results = [
        'total' => $total_score,
        'max' => $max_score,
        'percent' => $percent,
        'per_question' => $per_question_scores
    ];
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Bài thi trắc nghiệm (đọc từ Quiz.txt)</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body { font-family: Arial, sans-serif; background:#f6f7fb; color:#111; padding:20px; }
    .card { background:#fff; padding:18px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06); max-width:900px; margin:12px auto; }
    h1 { margin-top:0; }
    .question { padding:12px; border-bottom:1px solid #eee; }
    .qtext { font-weight:600; margin-bottom:8px; }
    .opts { margin-left:8px; }
    .submit-row { text-align:center; margin:18px 0; }
    button { padding:10px 18px; border-radius:6px; border:0; background:#246; color:#fff; cursor:pointer; }
    .result { background:#fff8db; border:1px solid #f1e1a8; padding:12px; border-radius:6px; margin:12px 0; }
    .small { font-size:0.9rem; color:#555; }
    .correct { color: green; font-weight:700; }
    .wrong { color: red; font-weight:700; }
    .answer-note { font-size:0.95rem; margin-top:6px; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Bài thi trắc nghiệm</h1>
    <form method="post" action="">
      <?php foreach ($questions as $i => $q): 
        $is_multi = count($q['answers']) > 1;
      ?>
        <div class="question" id="q<?=$i?>">
          <div class="qtext">Câu <?=($i+1)?>. <?= h($q['q']) ?></div>
          <div class="opts">
            <?php foreach ($q['options'] as $letter => $opt): 
                $input_name = "ans[$i]" . ($is_multi ? "[]" : "");
                $input_id = "q{$i}_{$letter}";
                $type = $is_multi ? 'checkbox' : 'radio';
            ?>
              <div>
                <label>
                  <input type="<?= $type ?>" name="<?= h($input_name) ?>" id="<?= h($input_id) ?>" value="<?= h($letter) ?>">
                  <strong><?= h($letter) ?>.</strong> <?= h($opt) ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="answer-note small">(<?= $is_multi ? 'Chọn nhiều đáp án' : 'Chọn 1 đáp án' ?>)</div>

          <?php if ($results): 
                $pr = $results['per_question'][$i];
                // format selected string
                $sel = $pr['selected'];
                $sel_str = empty($sel) ? '<em>Không chọn</em>' : h(implode(', ', $sel));
                $correct_str = h(implode(', ', $pr['correct']));
                $score = $pr['score'];
          ?>
            <div class="answer-note" style="margin-top:10px;">
              Kết quả câu này: 
              <?php if ($score >= 1-1e-9): ?>
                <span class="correct">Đúng</span>
              <?php elseif ($score == 0): ?>
                <span class="wrong">Sai</span>
              <?php else: ?>
                <span class="small">Đúng một phần (điểm: <?=round($score,2)?>)</span>
              <?php endif; ?>
              &nbsp;|&nbsp; Bạn chọn: <?= $sel_str ?> &nbsp;|&nbsp; Đáp án đúng: <strong><?= $correct_str ?></strong>
            </div>
          <?php endif; ?>

        </div>
      <?php endforeach; ?>

      <div class="submit-row">
        <button type="submit">Nộp bài</button>
      </div>
    </form>

    <?php if ($results): ?>
      <div class="result">
        <div><strong>Tổng điểm:</strong> <?= h($results['total']) ?> / <?= h($results['max']) ?></div>
        <div><strong>Phần trăm:</strong> <?= h($results['percent']) ?>%</div>
        <div class="small" style="margin-top:6px;">Lưu ý: câu nhiều đáp án được chấm theo công thức: (số đáp án đúng chọn - số đáp án sai chọn) / (số đáp án đúng). Giá trị tối thiểu là 0. Một câu đơn đáp án chỉ được 1 điểm nếu đúng, 0 nếu sai.</div>
      </div>
    <?php endif; ?>

  </div>
</body>
</html>
