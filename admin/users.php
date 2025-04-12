<?php

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');

secure();

if(isset($_GET['delete'])) {

    $query = 'DELETE FROM users WHERE id = ' . $_GET['delete'] . ' LIMIT 1';
    mysqli_query($connect, $query);

    set_message('User has been deleted');
    header('Location: users.php');
    die();
}

include('includes/header.php');

$query = 'SELECT * FROM users 
    ' . (($_SESSION['id'] != 1 && $_SESSION['id'] != 4) ? 'WHERE id = ' . $_SESSION['id'] . ' ' : '') . '
    ORDER BY last, first';
$result = mysqli_query($connect, $query);

?>

<div class="container">
    <h2 class="my-4 text-center">Manage Users</h2>

    <div class="mb-3">
        <a href="users_add.php" class="btn-add">
            <i class="fas fa-plus-square"></i> Add User
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th width="80">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th width="150">Active</th>
                    <th width="200">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($record = mysqli_fetch_assoc($result)): ?>
                    <tr class="user-row">
                        <td class="text-center"><?= (int)$record['id']; ?></td>
                        <td><?= htmlentities($record['first']) . ' ' . htmlentities($record['last']); ?></td>
                        <td>
                            <a href="mailto:<?= htmlentities($record['email']); ?>">
                                <?= htmlentities($record['email']); ?>
                            </a>
                        </td>
                        <td class="text-center"><?= htmlentities($record['active']); ?></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="users_edit.php?id=<?= (int)$record['id']; ?>" class="btn btn-outline-info">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <?php if($_SESSION['id'] != $record['id']): ?>
                                    <a href="users.php?delete=<?= (int)$record['id']; ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
