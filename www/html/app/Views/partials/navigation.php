        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <?php if(session()->exists('user')): ?>
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="/auth/logout">Logout</a></li>
                <?php else: ?>
                <li><a href="/auth/login">Login</a></li>
                <li><a href="/auth/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
