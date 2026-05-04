<h1 class="title">User Details</h1>

<div class="card">
  <div class="card-content">
    <div class="content">
      <p><strong>ID:</strong> <?= $user->id ?></p>
      <p><strong>Name:</strong> <?= htmlspecialchars($user->name) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($user->email) ?></p>
    </div>
  </div>
  <footer class="card-footer">
    <a href="<?= url('/users') ?>" class="card-footer-item">Back to Users</a>
    <a href="<?= url('/users/' . $user->id . '/edit') ?>" class="card-footer-item">Edit</a>
  </footer>
</div>
