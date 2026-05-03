<?php
get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                the_title( '<h1 class="mt-5">', '</h1>' );
                the_content();
            endwhile;
        else :
            echo '<p class="mt-5">No posts found.</p>';
        endif;
        ?>
    </div>
</main>

<?php

get_footer();
?>
