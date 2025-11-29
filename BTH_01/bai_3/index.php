<?php
// Tên file CSV cần đọc
$filename = "dssv.csv";

// Kiểm tra file có tồn tại không
if (!file_exists($filename)) {
    die("Không tìm thấy tệp CSV: $filename");
}

// Mở file để đọc
$file = fopen($filename, "r");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách điểm danh lớp 65HTTT</title>

    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f6f9; 
            padding: 20px; 
        }

        /* Khung chứa tiêu đề + bảng để căn giữa */
        .container {
            width: 80%;
            margin: 0 auto; /* căn giữa */
        }

        table { 
            border-collapse: collapse; 
            width: 100%; 
            background: #fff; 
            margin-top: 10px;
        }

        th, td { 
            border: 1px solid #ccc; 
            padding: 10px; 
            text-align: left; 
        }

        th { 
            background: #2b7cff; 
            color: #fff; 
        }

        tr:nth-child(even) { 
            background: #f2f2f2; 
        }

        .white-nowrap {
            white-space: nowrap;
        }

        td.lastname-col {
            width: 200px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>📄 Danh sách điểm danh lớp 65 Hệ thống thông tin</h1>

    <table>
    <?php
    $isFirstRow = true; 
    $stt = 1;

    while (($row = fgetcsv($file, 1000, ",")) !== false) {

        echo "<tr>";

        if ($isFirstRow) {

            echo "<th>STT</th>";

            foreach ($row as $col) {
                echo "<th>" . htmlspecialchars($col) . "</th>";
            }

            $isFirstRow = false;

        } else {

            echo "<td><strong>$stt</strong></td>";
            $stt++;

            foreach ($row as $index => $col) {
                if ($index == 1) {
                    echo "<td class='lastname-col white-nowrap'>" . htmlspecialchars($col) . "</td>";
                } else {
                    echo "<td class='white-nowrap'>" . htmlspecialchars($col) . "</td>";
                }
            }
        }

        echo "</tr>";
    }

    fclose($file);
    ?>
    </table>
</div>

</body>
</html>
