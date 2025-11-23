<?php 
// TODO 1: (Cực kỳ quan trọng) Khởi động session 
session_start();
 
// TODO 2: Kiểm tra xem người dùng đã nhấn nút "Đăng nhập" (gửi form) chưa 
if(isset($_POST['username']) && isset($_POST['password'])){

    // TODO 3: Nếu đã gửi form, lấy dữ liệu 'username' và 'password' từ $_POST 
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // TODO 4: (Giả lập) Kiểm tra logic đăng nhập  
    if ($user == 'admin' && $pass == '123') { 
         
        // TODO 5: Nếu thành công, lưu tên username vào SESSION 
        $_SESSION['login_user'] = $user;

        // TODO 6: Chuyển hướng người dùng sang trang "chào mừng" 
        header('Location: welcome.php');
        // Và luôn gọi exit() ngay sau khi dùng header() 
        exit; 
         
    } else { 
        // Nếu thất bại, chuyển hướng về login.html 
        header('Location: login.html?error=1'); // Kèm theo thông báo lỗi trên URL
        exit; 
    } 
    // TODO 7: Nếu người dùng truy cập trực tiếp file này (không qua POST),  
    // "đá" họ về trang login.html 
}else{
    header('Location: login.html');
    exit;
}
?>
