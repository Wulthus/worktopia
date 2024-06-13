<?php

use Framework\Session;

$successMessage = Session::getMessage(Session::$successKey);
$errorMessage = Session::getMessage(Session::$errorKey);

?>

<?php if($successMessage !== null) : ?>
    <div class="message bg-green-100 p-3 my-3">
        <?=$successMessage ?>
    </div>
<?php endif; ?>

<?php if($errorMessage !== null) : ?>
    <div class="message bg-red-100 p-3 my-3">
        <?= $errorMessage ?>
    </div>
<?php endif; ?>