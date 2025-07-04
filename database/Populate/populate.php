<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\PostsPopulate;
use Database\Populate\UsersPopulate;

Database::migrate();

UsersPopulate::populate();
PostsPopulate::populate();
