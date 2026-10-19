<header class="bg-blue-600 text-white p-4 flex justify-between items-center">
    <div class="flex items-center space-x-3">
        <h1 class="text-xl font-bold">Delivery Express</h1>
    </div>
    <div>
        <?php if (isset($_SESSION['username'])): ?>
            <span class="mr-4 font-medium">Hi, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">Logout</a>
        <?php else: ?>
            <a href="login.php" class="text-white hover:text-blue-200 px-4 py-2 text-sm font-semibold transition-all">Login</a>
            <a href="register.php" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md">Sign Up</a>
        <?php endif; ?>
    </div>
</header>