<?php require APP_ROOT . '\App\Helpers\Session.php'; ?>

<button id="editProfileBtn">Edit Profile</button>

<!-- Modal -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>

        <h2>Edit Profile</h2>

        <form id="profileForm">
            <input type="hidden" name="id" value="
            <?= 
            // Session::get('user_id') 
            ?>
            ">

            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Address</label>
            <textarea name="address"><?= htmlspecialchars($user['address']) ?></textarea>

            <button type="submit">Save Changes</button>
        </form>

        <p id="profileMsg"></p>
    </div>
</div>
