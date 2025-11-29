<?php require 'flowers.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách các loài hoa - Giao diện quản trị</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border-left: 5px solid #3498db;
        }
        
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .form-container h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: default;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .back-link {
            display: inline-block;
            padding: 12px 25px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 18px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        th {
            background: #3498db;
            color: white;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        tr:hover {
            background: #e3f2fd;
        }
        
        .flower-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }
        
        .action-buttons {
            white-space: nowrap;
        }
        
        .btn-warning, .btn-danger {
            display: inline-block;
            padding: 8px 15px;
            margin: 2px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            border: none;
            cursor: default;
        }
        
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Nhập thông tin để thêm hoa mới</h2>
            <form>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Tên loại hoa : </label>
                        <input type="text" id="name" name="name" placeholder="Nhập tên hoa">
                    </div>
                    <div class="form-group">
                        <label for="image">Ảnh của hoa</label>
                        <input type="text" id="image" name="image" placeholder="Ví dụ: hoa_moi.jpg">
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Mô tả:</label>
                    <textarea id="description" name="desc" placeholder="Nhập mô tả về loài hoa"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Thêm hoa mới</button>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên hoa</th>
                    <th>Mô tả</th>
                    <th>Ảnh</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flowers as $id => $flower): ?>
                <tr>
                    <td><?php echo $id; ?></td>
                    <td><?php echo htmlspecialchars($flower['name']); ?></td>
                    <td><?php echo htmlspecialchars($flower['description']); ?></td>
                    <td><img src="images/<?php echo htmlspecialchars($flower['image']); ?>" alt="<?php echo htmlspecialchars($flower['name']); ?>" class="flower-image"></td>
                    <td class="action-buttons">
                        <span class="btn-warning">Sửa</span>
                        <span class="btn-danger">Xóa</span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>