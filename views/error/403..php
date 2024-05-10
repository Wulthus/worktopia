<?php
    require basePath("views/partials/head.php")
?>

<!-- Nav -->
<?php loadPartial("nav-bar") ?>
    <section>
        <div class="container mx-auto p-4 mt-4">
            <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3">403 Error</div>
            <p class="text-center text-2xl mb-4">
                You are not authorised to view this page.
            </p>
        </div>
    </section>
<!-- Footer -->
<?php loadPartial("footer") ?>