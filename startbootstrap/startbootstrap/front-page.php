<?php
/* Template Name: Front Page */
get_header();

// Get homepage ID and theme URI
$homepage_id = get_option('page_on_front') ?: get_the_ID();
$theme_uri   = get_template_directory_uri();

// ─── ACF HELPERS ─────────────────────────────────────────────────────────────
// Safe wrapper: returns ACF field value if ACF is active, else returns $default
function agency_field( $key, $post_id = false, $default = '' ) {
    if ( function_exists('get_field') ) {
        $val = get_field( $key, $post_id );
        if ( $val !== false && $val !== null && $val !== '' ) {
            return $val;
        }
    }
    return $default;
}

function agency_rows( $key, $post_id = false ) {
    if ( function_exists('have_rows') && have_rows( $key, $post_id ) ) {
        return true;
    }
    return false;
}
?>

<!-- ═══════════════════════════════════════════════════════
     MASTHEAD
════════════════════════════════════════════════════════ -->
<header class="masthead">
    <div class="container">
        <?php
        $subheading = agency_field( 'heading',     $homepage_id, 'Welcome To Our Studio!' );
        $heading    = agency_field( 'sub_heading',  $homepage_id, "It's Nice To Meet You" );
        ?>
        <div class="masthead-subheading"><?php echo esc_html( $subheading ); ?></div>
        <div class="masthead-heading text-uppercase"><?php echo esc_html( $heading ); ?></div>
        <a class="btn btn-primary btn-xl text-uppercase" href="#services">Tell Me More</a>
    </div>
</header>

<!-- ═══════════════════════════════════════════════════════
     SERVICES
════════════════════════════════════════════════════════ -->
<section class="page-section" id="services">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Services</h2>
            <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
        </div>
        <div class="row text-center">

            <?php if ( agency_rows( 'services', $homepage_id ) ) : ?>

                <?php while ( have_rows( 'services', $homepage_id ) ) : the_row(); ?>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="<?php the_sub_field('icon_class'); ?> fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3"><?php the_sub_field('title'); ?></h4>
                        <p class="text-muted"><?php the_sub_field('description'); ?></p>
                    </div>
                <?php endwhile; ?>

            <?php else : ?>
                <!-- Default Services (matches index.html exactly) -->
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">E-Commerce</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Responsive Design</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fas fa-circle fa-stack-2x text-primary"></i>
                        <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Web Security</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     PORTFOLIO GRID
════════════════════════════════════════════════════════ -->
<section class="page-section bg-light" id="portfolio">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Portfolio</h2>
            <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
        </div>
        <div class="row">

            <?php if ( agency_rows( 'portfolio_items', $homepage_id ) ) : ?>

                <?php $count = 1; while ( have_rows( 'portfolio_items', $homepage_id ) ) : the_row(); ?>
                    <div class="col-lg-4 col-sm-6 mb-4">
                        <div class="portfolio-item">
                            <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal<?php echo $count; ?>">
                                <div class="portfolio-hover">
                                    <div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>
                                </div>
                                <img class="img-fluid" src="<?php the_sub_field('thumbnail'); ?>" alt="..." />
                            </a>
                            <div class="portfolio-caption">
                                <div class="portfolio-caption-heading"><?php the_sub_field('title'); ?></div>
                                <div class="portfolio-caption-subheading text-muted"><?php the_sub_field('category'); ?></div>
                            </div>
                        </div>
                    </div>
                <?php $count++; endwhile; ?>

            <?php else : ?>
                <!-- Default Portfolio Items (matches index.html exactly — all 6) -->
                <?php
                $portfolio_defaults = [
                    1 => [ 'title' => 'Threads',   'cat' => 'Illustration',   'extra_class' => '' ],
                    2 => [ 'title' => 'Explore',   'cat' => 'Graphic Design', 'extra_class' => '' ],
                    3 => [ 'title' => 'Finish',    'cat' => 'Identity',       'extra_class' => '' ],
                    4 => [ 'title' => 'Lines',     'cat' => 'Branding',       'extra_class' => 'mb-lg-0' ],
                    5 => [ 'title' => 'Southwest', 'cat' => 'Website Design', 'extra_class' => 'mb-sm-0' ],
                    6 => [ 'title' => 'Window',    'cat' => 'Photography',    'extra_class' => '' ],
                ];
                foreach ( $portfolio_defaults as $num => $item ) :
                ?>
                <div class="col-lg-4 col-sm-6 mb-4 <?php echo esc_attr( $item['extra_class'] ); ?>">
                    <div class="portfolio-item">
                        <a class="portfolio-link" data-bs-toggle="modal" href="#portfolioModal<?php echo $num; ?>">
                            <div class="portfolio-hover">
                                <div class="portfolio-hover-content"><i class="fas fa-plus fa-3x"></i></div>
                            </div>
                            <img class="img-fluid" src="<?php echo esc_url( $theme_uri . '/assets/img/portfolio/' . $num . '.jpg' ); ?>" alt="..." />
                        </a>
                        <div class="portfolio-caption">
                            <div class="portfolio-caption-heading"><?php echo esc_html( $item['title'] ); ?></div>
                            <div class="portfolio-caption-subheading text-muted"><?php echo esc_html( $item['cat'] ); ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     ABOUT / TIMELINE
════════════════════════════════════════════════════════ -->
<section class="page-section" id="about">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">About</h2>
            <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
        </div>
        <ul class="timeline">

            <?php if ( agency_rows( 'timeline', $homepage_id ) ) : ?>

                <?php while ( have_rows( 'timeline', $homepage_id ) ) : the_row(); ?>
                    <li class="<?php echo get_sub_field('inverted') ? 'timeline-inverted' : ''; ?>">
                        <div class="timeline-image">
                            <img class="rounded-circle img-fluid" src="<?php the_sub_field('image'); ?>" alt="..." />
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4><?php the_sub_field('year'); ?></h4>
                                <h4 class="subheading"><?php the_sub_field('title'); ?></h4>
                            </div>
                            <div class="timeline-body">
                                <p class="text-muted"><?php the_sub_field('description'); ?></p>
                            </div>
                        </div>
                    </li>
                <?php endwhile; ?>

            <?php else : ?>
                <!-- Default Timeline (matches index.html exactly — all 4 entries) -->
                <li>
                    <div class="timeline-image">
                        <img class="rounded-circle img-fluid" src="<?php echo esc_url( $theme_uri . '/assets/img/about/1.jpg' ); ?>" alt="..." />
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>2009-2011</h4>
                            <h4 class="subheading">Our Humble Beginnings</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-image">
                        <img class="rounded-circle img-fluid" src="<?php echo esc_url( $theme_uri . '/assets/img/about/2.jpg' ); ?>" alt="..." />
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>March 2011</h4>
                            <h4 class="subheading">An Agency is Born</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="timeline-image">
                        <img class="rounded-circle img-fluid" src="<?php echo esc_url( $theme_uri . '/assets/img/about/3.jpg' ); ?>" alt="..." />
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>December 2015</h4>
                            <h4 class="subheading">Transition to Full Service</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-image">
                        <img class="rounded-circle img-fluid" src="<?php echo esc_url( $theme_uri . '/assets/img/about/4.jpg' ); ?>" alt="..." />
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>July 2020</h4>
                            <h4 class="subheading">Phase Two Expansion</h4>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam, recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!</p>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Always show this last item -->
            <li class="timeline-inverted">
                <div class="timeline-image">
                    <h4>Be Part<br />Of Our<br />Story!</h4>
                </div>
            </li>

        </ul>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     TEAM
════════════════════════════════════════════════════════ -->
<section class="page-section bg-light" id="team">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Our Amazing Team</h2>
            <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
        </div>
        <div class="row">

            <?php if ( agency_rows( 'team_members', $homepage_id ) ) : ?>

                <?php while ( have_rows( 'team_members', $homepage_id ) ) : the_row(); ?>
                    <div class="col-lg-4">
                        <div class="team-member">
                            <img class="mx-auto rounded-circle" src="<?php the_sub_field('photo'); ?>" alt="..." />
                            <h4><?php the_sub_field('name'); ?></h4>
                            <p class="text-muted"><?php the_sub_field('role'); ?></p>
                            <a class="btn btn-dark btn-social mx-2" href="<?php the_sub_field('twitter'); ?>" aria-label="Twitter Profile"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-dark btn-social mx-2" href="<?php the_sub_field('facebook'); ?>" aria-label="Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-dark btn-social mx-2" href="<?php the_sub_field('linkedin'); ?>" aria-label="LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                <?php endwhile; ?>

            <?php else : ?>
                <!-- Default Team Members (matches index.html exactly) -->
                <div class="col-lg-4">
                    <div class="team-member">
                        <img class="mx-auto rounded-circle" src="<?php echo esc_url( $theme_uri . '/assets/img/team/1.jpg' ); ?>" alt="..." />
                        <h4>Parveen Anand</h4>
                        <p class="text-muted">Lead Designer</p>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Parveen Anand Twitter Profile"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Parveen Anand Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Parveen Anand LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="team-member">
                        <img class="mx-auto rounded-circle" src="<?php echo esc_url( $theme_uri . '/assets/img/team/2.jpg' ); ?>" alt="..." />
                        <h4>Diana Petersen</h4>
                        <p class="text-muted">Lead Marketer</p>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Diana Petersen Twitter Profile"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Diana Petersen Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Diana Petersen LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="team-member">
                        <img class="mx-auto rounded-circle" src="<?php echo esc_url( $theme_uri . '/assets/img/team/3.jpg' ); ?>" alt="..." />
                        <h4>Larry Parker</h4>
                        <p class="text-muted">Lead Developer</p>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Larry Parker Twitter Profile"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Larry Parker Facebook Profile"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-dark btn-social mx-2" href="#!" aria-label="Larry Parker LinkedIn Profile"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <p class="large text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     CLIENTS
════════════════════════════════════════════════════════ -->
<div class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 col-sm-6 my-3">
                <a href="#!"><img class="img-fluid img-brand d-block mx-auto" src="<?php echo esc_url( $theme_uri . '/assets/img/logos/microsoft.svg' ); ?>" alt="..." aria-label="Microsoft Logo" /></a>
            </div>
            <div class="col-md-3 col-sm-6 my-3">
                <a href="#!"><img class="img-fluid img-brand d-block mx-auto" src="<?php echo esc_url( $theme_uri . '/assets/img/logos/google.svg' ); ?>" alt="..." aria-label="Google Logo" /></a>
            </div>
            <div class="col-md-3 col-sm-6 my-3">
                <a href="#!"><img class="img-fluid img-brand d-block mx-auto" src="<?php echo esc_url( $theme_uri . '/assets/img/logos/facebook.svg' ); ?>" alt="..." aria-label="Facebook Logo" /></a>
            </div>
            <div class="col-md-3 col-sm-6 my-3">
                <a href="#!"><img class="img-fluid img-brand d-block mx-auto" src="<?php echo esc_url( $theme_uri . '/assets/img/logos/ibm.svg' ); ?>" alt="..." aria-label="IBM Logo" /></a>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     CONTACT
════════════════════════════════════════════════════════ -->
<section class="page-section" id="contact">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase"><?php echo function_exists('get_field') ? get_field('contact_heading') : 'Contact Us'; ?>Contact Us</h2>
            <h3 class="section-subheading text-muted"><?php echo function_exists('get_field') ? get_field('contact_subheading') : 'Get in touch with us'; ?></h3>
        </div>

        <form id="contactForm" data-sb-form-api-token="<?php echo function_exists('get_field') ? get_field('contact_api_token') : ''; ?>">
            
            <!-- 1. ADDED SECURITY NONCE FIELD HERE -->
            <?php wp_nonce_field('contact_form_nonce', 'contact_form_security'); ?>

            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <!-- 2. ADDED name="u_name" -->
                    <input class="form-control mb-3" id="name" name="u_name" type="text" placeholder="Your Name *" required>
                    
                    <!-- 3. ADDED name="u_email" -->
                    <input class="form-control mb-3" id="email" name="u_email" type="email" placeholder="Your Email *" required>
                    
                    <!-- 4. ADDED name="u_phone" -->
                    <input class="form-control mb-3" id="phone" name="u_phone" type="tel" placeholder="Your Phone *" required>
                </div>
                <div class="col-md-6">
                    <!-- 5. ADDED name="u_message" -->
                    <textarea class="form-control h-100" id="message" name="u_message" placeholder="Your Message *" required></textarea>
                </div>
            </div>
            <div class="text-center">
                <div id="form-feedback"></div>
                <button class="btn btn-primary btn-xl text-uppercase" id="submitButton" type="submit">Send Message</button>
            </div>
        </form>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     PORTFOLIO MODALS (all 6 — matches index.html exactly)
════════════════════════════════════════════════════════ -->
<?php
$modal_defaults = [
    1 => [ 'title' => 'Threads',   'cat' => 'Illustration',   'client' => 'Threads'   ],
    2 => [ 'title' => 'Explore',   'cat' => 'Graphic Design', 'client' => 'Explore'   ],
    3 => [ 'title' => 'Finish',    'cat' => 'Identity',       'client' => 'Finish'    ],
    4 => [ 'title' => 'Lines',     'cat' => 'Branding',       'client' => 'Lines'     ],
    5 => [ 'title' => 'Southwest', 'cat' => 'Website Design', 'client' => 'Southwest' ],
    6 => [ 'title' => 'Window',    'cat' => 'Photography',    'client' => 'Window'    ],
];

// If ACF portfolio items exist, override modal data
$acf_modals = [];
if ( agency_rows( 'portfolio_items', $homepage_id ) ) {
    $idx = 1;
    while ( have_rows( 'portfolio_items', $homepage_id ) ) {
        the_row();
        $acf_modals[ $idx ] = [
            'title'  => get_sub_field('title'),
            'cat'    => get_sub_field('category'),
            'client' => get_sub_field('client') ?: get_sub_field('title'),
            'image'  => get_sub_field('thumbnail'),
            'desc'   => get_sub_field('description'),
        ];
        $idx++;
    }
}

foreach ( $modal_defaults as $num => $item ) :
    $modal = isset( $acf_modals[ $num ] ) ? $acf_modals[ $num ] : null;
    $modal_title  = $modal ? $modal['title']  : 'Project Name';
    $modal_client = $modal ? $modal['client'] : $item['client'];
    $modal_cat    = $modal ? $modal['cat']    : $item['cat'];
    $modal_image  = $modal && ! empty( $modal['image'] ) ? $modal['image'] : $theme_uri . '/assets/img/portfolio/' . $num . '.jpg';
    $modal_desc   = $modal && ! empty( $modal['desc'] )  ? $modal['desc']  : 'Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!';
?>
<div class="portfolio-modal modal fade" id="portfolioModal<?php echo $num; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="close-modal" data-bs-dismiss="modal">
                <img src="<?php echo esc_url( $theme_uri . '/assets/img/close-icon.svg' ); ?>" alt="Close modal" />
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="modal-body">
                            <h2 class="text-uppercase"><?php echo esc_html( $modal_title ); ?></h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-fluid d-block mx-auto" src="<?php echo esc_url( $modal_image ); ?>" alt="..." />
                            <p><?php echo esc_html( $modal_desc ); ?></p>
                            <ul class="list-inline">
                                <li><strong>Client:</strong> <?php echo esc_html( $modal_client ); ?></li>
                                <li><strong>Category:</strong> <?php echo esc_html( $modal_cat ); ?></li>
                            </ul>
                            <button class="btn btn-primary btn-xl text-uppercase" data-bs-dismiss="modal" type="button">
                                <i class="fas fa-xmark me-1"></i>
                                Close Project
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php get_footer(); ?>