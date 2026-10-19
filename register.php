<?php
include('config/database.php');

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if($password != $confirm){
        $error = "Password does not match!";
    } else {
        mysqli_query($conn, "INSERT INTO users(username,password) VALUES('$username','$password')");
        header("Location: login.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl flex flex-col md:flex-row w-full max-w-4xl overflow-hidden">
        <!-- Left Side -->
        <div class="bg-blue-600 text-white p-8 md:p-12 md:w-1/2 flex flex-col justify-center relative">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Create<br>Account</h1>
            <p class="text-blue-100 text-sm md:text-base leading-relaxed">
                Join Delivery Express and manage your delivery system easily.
            </p>
        </div>

        <!-- Right Side (Form) -->
        <div class="p-8 md:p-12 md:w-1/2 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Sign Up</h2>
            
            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <input type="text" name="username" placeholder="qanea" required 
                        class="w-full px-4 py-3 rounded-xl bg-blue-50/50 border border-transparent focus:border-blue-500 focus:bg-white focus:outline-none transition-all text-gray-700">
                </div>
                <div>
                    <input type="password" name="password" placeholder="***" required 
                        class="w-full px-4 py-3 rounded-xl bg-blue-50/50 border border-transparent focus:border-blue-500 focus:bg-white focus:outline-none transition-all text-gray-700">
                </div>
                <div>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required 
                        class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-transparent focus:border-blue-500 focus:bg-white focus:outline-none transition-all text-gray-700">
                </div>

                <button type="submit" name="register" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-blue-500/30">
                    Sign Up
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-8">
                Already have account? <a href="login.php" class="text-blue-600 font-semibold hover:underline">Login</a>
            </p>
        </div>
    </div>
</body>
</html>