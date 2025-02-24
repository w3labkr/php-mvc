<?php include VIEW_PATH . '/partials/head.php'; ?>
<?php include VIEW_PATH . '/partials/header.php'; ?>

<main>
    <h1>Login</h1>
    <div id="message"></div>
    <form id="loginForm">
        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
        <p>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <label for="remember">Remember me</label>
            <input type="checkbox" name="remember" id="remember" value="1">
        </p>
        <p>
            <button type="submit">Login</button>
        </p>
    </form>
    <p><a href="/auth/forgot-password">Forgot your password?</a></p>
    <p>Don't have an account? <a href="/auth/register">Register here</a></p>
</main>

<script>
$(document).ready(function(){
    $("#loginForm").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            url: '/api/v1/auth/login',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if(res.success) {
                    window.location.href = '/dashboard';
                }
            },
            error: function(xhr) {
                const res = xhr.responseJSON;
                if (res.message) {
                    $("#message").html('<p style="color:red;">'+res.message+'</p>');
                } else {
                    $("#message").html('<p style="color:red;">An error occurred. Please try again later.</p>');
                }
            }
        });
    });
});
</script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
<?php include VIEW_PATH . '/partials/tail.php'; ?>