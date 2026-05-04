<h1 class="title">Create User</h1>

<div class="box">
    <form action="<?= url('/users') ?>" method="POST">
        <div class="field">
            <label class="label">Name</label>
            <div class="control">
                <input class="input" type="text" name="name" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Email</label>
            <div class="control">
                <input class="input" type="email" name="email" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Password</label>
            <div class="control">
                <input class="input" type="password" name="password" required>
            </div>
        </div>

        <div class="control mt-4">
            <button class="button is-primary" type="submit">Create User</button>
            <a href="<?= url('/users') ?>" class="button is-light">Cancel</a>
        </div>
    </form>
</div>
