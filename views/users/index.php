<h1 class="title">Users</h1>
<a href="<?= url('/users/create') ?>" class="button is-primary mb-4">Create New User</a>

<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= htmlspecialchars($user->name) ?></td>
                <td><?= htmlspecialchars($user->email) ?></td>
                <td>
                    <a href="<?= url('/users/' . $user->id) ?>" class="button is-info is-small">View</a>
                    <a href="<?= url('/users/' . $user->id . '/edit') ?>" class="button is-warning is-small">Edit</a>
                    <form action="<?= url('/users/' . $user->id) ?>" method="POST" style="display:inline;">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="button is-danger is-small" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
