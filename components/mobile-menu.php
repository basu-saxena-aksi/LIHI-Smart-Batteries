<section class="menu-bar" id="mobile-menu" inert>

        <div class="menu-section">
            <div class="menu-container">
                <div class="menu-header">
                    <div class="logo">
                        <a href="<?php echo esc_url( home_url('/')); ?>">
                            <img
                            src="<?php echo esc_url(
                                content_url('/uploads/2026/10/LiHi-Smart-Batteries-Logo.png')
                            ); ?>"
                            alt="LiHi Smart Batteries"
                            class="logo-image"
                        >
                        </a>
                    </div>

                    <button type="button" class="menu-close" aria-label="Close menu">
                        <i data-lucide="x"></i>
                    </button>
                </div>

                <nav class="menu-nav-links" aria-label="Mobile navigation">
                    
                    <div class="mobile-menu-item">

                        <div class="mobile-menu-row">
                            <a href="<?php echo esc_url(home_url('/about-us/')); ?>">
                                About Us
                            </a>

                            <button
                                type="button"
                                class="mobile-dropdown-toggle"
                                aria-label="Toggle About Us submenu"
                                aria-expanded="false"
                            >
                                <svg viewBox="0 0 10 6" fill="none">
                                    <path d="M1 1l4 4 4-4"
                                        stroke="currentColor"
                                        stroke-width="1.5"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mobile-submenu" inert>
                            <div class="mobile-submenu-inner">
                                <a href="<?php echo esc_url(home_url('/about-us/')); ?>">
                                Overview
                                </a>
                                <a href="<?php echo esc_url(home_url('/our-team/')); ?>">
                                    Our Team
                                </a>
                                <a href="<?php echo esc_url(home_url('/our-vision/')); ?>">
                                    Our Vision
                                </a>
                            </div>
                            
                        </div>

                    </div>

                    
                    <div class="mobile-menu-item">

                        <div class="mobile-menu-row">
                            <a href="<?php echo esc_url(home_url('/products/')); ?>">
                                Products
                            </a>

                            <button
                                type="button"
                                class="mobile-dropdown-toggle"
                                aria-label="Toggle Products submenu"
                                aria-expanded="false"
                            >
                                <svg viewBox="0 0 10 6" fill="none">
                                    <path d="M1 1l4 4 4-4"
                                        stroke="currentColor"
                                        stroke-width="1.5"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mobile-submenu" inert>
                            <div class="mobile-submenu-inner">

                                <?php
                                $products = get_posts([
                                    'post_type'      => 'product',
                                    'post_status'    => 'publish',
                                    'posts_per_page' => -1,
                                    'orderby'        => 'title',
                                    'order'          => 'ASC',
                                ]);

                                if (!empty($products)) :
                                ?>

                                    <ul class="mobile-product-list">

                                        <?php foreach ($products as $product) : ?>
                                            <li>
                                                <a href="<?php echo esc_url(get_permalink($product->ID)); ?>">
                                                    <?php echo esc_html($product->post_title); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>

                                    </ul>

                                <?php endif; ?>

                            </div>
                        </div>

                    </div>


                    
                    <div class="mobile-menu-item">

                        <div class="mobile-menu-row">
                            <a href="<?php echo esc_url(home_url('/industries/')); ?>">
                                Industries
                            </a>

                            <button
                                type="button"
                                class="mobile-dropdown-toggle"
                                aria-label="Toggle Industries submenu"
                                aria-expanded="false"
                            >
                                <svg viewBox="0 0 10 6" fill="none">
                                    <path d="M1 1l4 4 4-4"
                                        stroke="currentColor"
                                        stroke-width="1.5"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mobile-submenu" inert>
                            <div class="mobile-submenu-inner">

                                <?php
                                $industries = get_posts([
                                    'post_type'      => 'industry',
                                    'post_status'    => 'publish',
                                    'posts_per_page' => -1,
                                    'orderby'        => 'title',
                                    'order'          => 'ASC',
                                ]);

                                if (!empty($industries)) :
                                ?>

                                    <ul class="mobile-product-list">

                                        <?php foreach ($industries as $industry) : ?>
                                            <li>
                                                <a href="<?php echo esc_url(get_permalink($industry->ID)); ?>">
                                                    <?php echo esc_html($industry->post_title); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>

                                    </ul>

                                <?php endif; ?>

                            </div>
                        </div>

                    </div>


                    
                    <div class="mobile-menu-item">

                        <div class="mobile-menu-row">
                            <a href="<?php echo esc_url(home_url('/applications/')); ?>">
                                Applications
                            </a>

                            <button
                                type="button"
                                class="mobile-dropdown-toggle"
                                aria-label="Toggle Applications submenu"
                                aria-expanded="false"
                            >
                                <svg viewBox="0 0 10 6" fill="none">
                                    <path d="M1 1l4 4 4-4"
                                        stroke="currentColor"
                                        stroke-width="1.5"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mobile-submenu" inert>
                            <div class="mobile-submenu-inner">

                                <?php
                                $applications = get_posts([
                                    'post_type'      => 'application',
                                    'post_status'    => 'publish',
                                    'posts_per_page' => -1,
                                    'orderby'        => 'title',
                                    'order'          => 'ASC',
                                ]);

                                if (!empty($applications)) :
                                ?>

                                    <ul class="mobile-product-list">

                                        <?php foreach ($applications as $application) : ?>
                                            <li>
                                                <a href="<?php echo esc_url(get_permalink($application->ID)); ?>">
                                                    <?php echo esc_html($application->post_title); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>

                                    </ul>

                                <?php endif; ?>

                            </div>
                        </div>

                    </div>

                    
                    <div class="mobile-menu-item">

                        <div class="mobile-menu-row">
                            <a href="<?php echo esc_url(home_url('/media/')); ?>">
                                Media
                            </a>

                            <button
                                type="button"
                                class="mobile-dropdown-toggle"
                                aria-label="Toggle Media submenu"
                                aria-expanded="false"
                            >
                                <svg viewBox="0 0 10 6" fill="none">
                                    <path d="M1 1l4 4 4-4"
                                        stroke="currentColor"
                                        stroke-width="1.5"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mobile-submenu" inert>
                            <div class="mobile-submenu-inner">

                                <a href="<?php echo esc_url(home_url('/blogs/')); ?>">
                                    Blogs
                                </a>

                                <a href="<?php echo esc_url(home_url('/news/')); ?>">
                                    News
                                </a>

                                <a href="<?php echo esc_url(home_url('/gallery/')); ?>">
                                    Gallery
                                </a>

                            </div>
                        </div>

                    </div>


                </nav>
            </div>
            <a href="<?php echo esc_url( home_url('/contact-us/')); ?>" class="btn btn-primary">Contact Us
                <svg viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
            </a>
        </div>
</section>