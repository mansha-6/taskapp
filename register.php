<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: dashboard.php');
    exit();
}

require_once __DIR__ . '/db.php';

if (!isset($conn)) {
    die('Database connection not established.');
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim(mysqli_real_escape_string($conn, $_POST['name'])) : '';
    $password = isset($_POST['pass']) ? mysqli_real_escape_string($conn, $_POST['pass']) : '';
    $confirm_password = isset($_POST['confirm_pass']) ? mysqli_real_escape_string($conn, $_POST['confirm_pass']) : '';

    if ($name === '' || $password === '' || $confirm_password === '') {
        $_SESSION['error'] = "Please fill all required fields.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        // Check if username already exists
        $check_sql = "SELECT id FROM users WHERE name='$name'";
        $check_result = mysqli_query($conn, $check_sql);

        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $_SESSION['error'] = "Username already taken.";
        } else {
            // Insert new user (Using plaintext to match existing login.php setup)
            $insert_sql = "INSERT INTO users (name, password) VALUES ('$name', '$password')";
            if (mysqli_query($conn, $insert_sql)) {
                $_SESSION['success'] = "Registration successful! Please sign in.";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = "Registration failed. Please try again.";
            }
        }
    }
    header("Location: register.php");
    exit();
}

include("includes/header.php");
?>

<div
class="min-h-screen flex justify-center items-center bg-cover bg-center"
style="background-image:url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1400&auto=format&fit=crop');">

<div class="bg-white/30 backdrop-blur-xl p-8 rounded-3xl w-[380px] shadow-2xl border border-white/20">

<h1 class="text-3xl font-extrabold text-center text-white mb-8 tracking-tight">
Sign Up
</h1>

<?php
if (isset($_SESSION['error'])) {
    echo '<div class="bg-rose-500/80 backdrop-blur-sm text-white p-3 rounded-xl mb-4 text-sm text-center font-medium shadow-lg shadow-rose-500/10">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
}
?>

<form action="register.php" method="POST" class="space-y-4">

<input
type="text"
name="name"
placeholder="Enter your name"
class="w-full p-4 rounded-xl outline-none bg-white/90 border border-slate-200/50 shadow-sm focus:bg-white text-slate-800 transition" required>

<input
type="password"
name="pass"
placeholder="Enter your password"
class="w-full p-4 rounded-xl outline-none bg-white/90 border border-slate-200/50 shadow-sm focus:bg-white text-slate-800 transition" required>

<input
type="password"
name="confirm_pass"
placeholder="Confirm your password"
class="w-full p-4 rounded-xl outline-none bg-white/90 border border-slate-200/50 shadow-sm focus:bg-white text-slate-800 transition" required>

<button
type="submit"
class="w-full bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-xl font-bold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 hover:-translate-y-0.5 transform transition duration-200 cursor-pointer">
Sign Up
</button>

<div class="text-center mt-6">
    <a href="index.php" class="text-white text-sm hover:underline font-semibold transition">
        Already have an account? Sign In
    </a>
</div>

</form>

</div>

</div>

<?php include("includes/footer.php"); ?>
