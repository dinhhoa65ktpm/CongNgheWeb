<?php require 'flowers.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách các loài hoa</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
            padding: 20px; 
        }
        
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 50px; 
        }
        
        .header h1 { 
            color: #2c3e50; 
            font-size: 2.5em; 
        }
        
        .flower-container {
            margin-bottom: 40px;
        }
        
        .flower-frame {
            background: white;
            border-radius: 15px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .flower-name {
            font-size: 28px;
            color: #e74c3c;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        
        .flower-content {
            max-width: 600px;
            margin: 0 auto 30px auto;
        }
        
        .flower-description {
            color: #555;
            line-height: 1.8;
            font-size: 16px;
            text-align: left;
        }
        
        .flower-image {
            width: 100%;
            max-width: 600px;
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .admin-link {
            text-align: center;
            margin-top: 50px;
        }
        
        .admin-btn {
            display: inline-block;
            padding: 15px 40px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .admin-btn:hover {
            background: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(52, 152, 219, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Các Loại Hoa Tuyệt Đẹp Thích Hợp Trồng Để Khoe Hương Sắc Dịp Xuân Hè</h1>
        </div>
        
        <?php foreach ($flowers as $flower): ?>
        <div class="flower-container">
            <div class="flower-frame">
                <h2 class="flower-name"><?php echo htmlspecialchars($flower['name']); ?></h2>
                
                <div class="flower-content">
                    <div class="flower-description">
                        <?php echo nl2br(htmlspecialchars($flower['description'])); ?>
                    </div>
                </div>
                
                <img src="images/<?php echo htmlspecialchars($flower['image']); ?>" 
                     alt="<?php echo htmlspecialchars($flower['name']); ?>" 
                     class="flower-image">
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>