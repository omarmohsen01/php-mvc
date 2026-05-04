<nav class="navbar" role="navigation" aria-label="main navigation">
  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <a class="navbar-item" href="/">
        Home
      </a>

      <a class="navbar-item">
        Documentation
      </a>

      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          More
        </a>

        <div class="navbar-dropdown">
          <a class="navbar-item">
            About
          </a>
          <a class="navbar-item">
            Jobs
          </a>
          <a class="navbar-item">
            Contact
          </a>
          <hr class="navbar-divider">
          <a class="navbar-item">
            Report an issue
          </a>
        </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <?php if (empty($_SESSION['user_id'])): ?>
              <a class="button is-primary" href="<?= url('/register') ?>">
                <strong>Register</strong>
              </a>
              <a class="button is-light" href="<?= url('/login') ?>">
                Log in
              </a>
          <?php else: ?>
              <form action="<?= url('/logout') ?>" method="POST">
                  <button type="submit" class="button is-danger">
                    Log out
                  </button>
              </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</nav>