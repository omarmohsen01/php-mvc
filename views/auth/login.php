<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form action="<?= url('/login') ?>" method="POST">
                        <div class="form-group mb-3">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="<?= url('/register') ?>">Don't have an account? Register here.</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
