<h1 class="title">Edit User</h1>

<div class="box">
    <form action="<?= url('/users/' . $user->id) ?>" method="POST">
        <input type="hidden" name="_method" value="PUT">
        
        <div class="field">
            <label class="label">Name</label>
            <div class="control">
                <input class="input" type="text" name="name" value="<?= htmlspecialchars($user->name) ?>" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Email</label>
            <div class="control">
                <input class="input" type="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
            </div>
        </div>

        <div class="field">
            <label class="label">Password (leave blank to keep current)</label>
            <div class="control">
                <input class="input" type="password" name="password">
            </div>
        </div>

        <div class="control mt-4">
            <button class="button is-primary" type="submit">Update User</button>
            <a href="<?= url('/users') ?>" class="button is-light">Cancel</a>
        </div>
    </form>
</div>
