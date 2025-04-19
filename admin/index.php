<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

// 检查是否已登录
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// 处理图片上传
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $image_type = $_POST['image_type'] ?? '';
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_error = $_FILES['image']['error'];
    
    // 检查上传错误
    if($image_error === UPLOAD_ERR_OK) {
        // 获取文件扩展名
        $file_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        
        // 允许的文件类型
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        
        if(in_array($file_ext, $allowed_types)) {
            // 根据图片类型设置目标文件名
            $target_name = '';
            switch($image_type) {
                case 'slider':
                    $target_name = $_POST['slider_name'] ?? $image_name;
                    break;
                case 'floor_plan':
                    $target_name = 'floor-plan.jpg';
                    break;
                case 'fengshui':
                    $target_name = 'fengshui-diagram.jpg';
                    break;
                case 'advantage':
                    $target_name = $_POST['advantage_name'] ?? $image_name;
                    break;
                case 'qrcode':
                    $target_name = 'qrcode.jpg';
                    break;
                default:
                    $target_name = $image_name;
            }
            
            // 确保目标文件名是唯一的
            $target_name = uniqid() . '.' . $file_ext;
            
            // 上传图片
            $target_path = '../images/' . $target_name;
            if(move_uploaded_file($image_tmp, $target_path)) {
                $success_message = '图片上传成功！';
            } else {
                $error_message = '图片上传失败，请检查文件权限。';
            }
        } else {
            $error_message = '不支持的文件类型，请上传 JPG、PNG 或 GIF 格式的图片。';
        }
    } else {
        $error_message = '图片上传出错，请重试。';
    }
}

// 处理图片删除
if(isset($_GET['delete'])) {
    $image_name = $_GET['delete'];
    $image_path = '../images/' . $image_name;
    
    if(file_exists($image_path) && unlink($image_path)) {
        $success_message = '图片删除成功！';
    } else {
        $error_message = '图片删除失败，请检查文件权限。';
    }
}

// 获取图片列表
$images = [];
$image_dir = '../images/';
if(is_dir($image_dir)) {
    $files = scandir($image_dir);
    foreach($files as $file) {
        if($file !== '.' && $file !== '..' && is_file($image_dir . $file)) {
            $images[] = $file;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理系统 - 图片管理</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>后台管理系统</h2>
            <ul class="nav-menu">
                <li><a href="index.php">图片管理</a></li>
                <li><a href="logout.php">退出登录</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="page-header">
                <h1>图片管理</h1>
                <a href="logout.php" class="logout-btn">退出登录</a>
            </div>
            
            <?php if(isset($success_message)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            
            <?php if(isset($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <div class="upload-form">
                <h2>上传新图片</h2>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-row">
                        <label for="image_type">图片类型：</label>
                        <select name="image_type" id="image_type" required>
                            <option value="">请选择图片类型</option>
                            <option value="slider">轮播图</option>
                            <option value="floor_plan">房屋平面图</option>
                            <option value="fengshui">风水示意图</option>
                            <option value="advantage">房屋优势图片</option>
                            <option value="qrcode">微信二维码</option>
                        </select>
                    </div>
                    
                    <div class="form-row" id="slider_name_row" style="display: none;">
                        <label for="slider_name">轮播图名称：</label>
                        <input type="text" name="slider_name" id="slider_name" placeholder="例如：living-room">
                    </div>
                    
                    <div class="form-row" id="advantage_name_row" style="display: none;">
                        <label for="advantage_name">优势图片名称：</label>
                        <input type="text" name="advantage_name" id="advantage_name" placeholder="例如：advantage-1">
                    </div>
                    
                    <div class="form-row">
                        <label for="image">选择图片：</label>
                        <input type="file" name="image" id="image" accept="image/*" required>
                    </div>
                    
                    <button type="submit">上传图片</button>
                </form>
            </div>
            
            <div class="image-preview">
                <h2>已上传图片</h2>
                <?php foreach($images as $image): ?>
                    <div class="preview-item">
                        <img src="../images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($image); ?>">
                        <div class="image-name"><?php echo htmlspecialchars($image); ?></div>
                        <a href="?delete=<?php echo urlencode($image); ?>" class="delete-btn" onclick="return confirm('确定要删除这张图片吗？')">删除</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <script>
        // 根据选择的图片类型显示/隐藏相应的输入框
        document.getElementById('image_type').addEventListener('change', function() {
            const sliderNameRow = document.getElementById('slider_name_row');
            const advantageNameRow = document.getElementById('advantage_name_row');
            
            sliderNameRow.style.display = this.value === 'slider' ? 'block' : 'none';
            advantageNameRow.style.display = this.value === 'advantage' ? 'block' : 'none';
        });
    </script>
</body>
</html> 