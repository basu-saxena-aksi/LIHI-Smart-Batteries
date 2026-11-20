
<?php get_header(); ?>

<main class="lihi-404-page">

    <section class="lihi-404-section">

        <div class="wrap">

            <div class="lihi-404-grid">

                <div class="lihi-404-content">

                    <span class="lihi-404-number">
                        404
                    </span>

                    <h1>Page Not Found</h1>

                    <p>
                        The page you're looking for might have been
                        moved, deleted, or the URL might be incorrect.
                    </p>

                    <a
                        href="<?php echo esc_url(home_url('/')); ?>"
                        class="lihi-404-btn"
                    >
                        <i data-lucide="arrow-left"></i>
                        Back to Homepage
                    </a>

                </div>

                <div class="lihi-404-art" aria-hidden="true">

                    <svg
                        class="lihi-404-illustration"
                        viewBox="0 0 460 340"
                        xmlns="http://www.w3.org/2000/svg"
                    >

                        <ellipse
                            cx="230"
                            cy="304"
                            rx="180"
                            ry="16"
                            fill="#EAF2FF"
                        />

                        <rect
                            x="78"
                            y="54"
                            width="300"
                            height="218"
                            rx="16"
                            fill="#FFFFFF"
                            stroke="#729BDD"
                            stroke-width="5"
                        />

                        <path
                            d="M78 70 Q78 54 94 54 H362 Q378 54 378 70 V95 H78 Z"
                            fill="#DCEBFF"
                        />

                        <line
                            x1="78"
                            y1="95"
                            x2="378"
                            y2="95"
                            stroke="#729BDD"
                            stroke-width="4"
                        />

                        <circle cx="104" cy="75" r="6" fill="#FFFFFF"/>
                        <circle cx="124" cy="75" r="6" fill="#FFFFFF"/>
                        <circle cx="144" cy="75" r="6" fill="#FFFFFF"/>

                        <circle cx="185" cy="166" r="7" fill="#163B83"/>
                        <circle cx="271" cy="166" r="7" fill="#163B83"/>

                        <path
                            d="M195 224 Q228 186 261 224"
                            fill="none"
                            stroke="#163B83"
                            stroke-width="6"
                            stroke-linecap="round"
                        />

                        <path
                            d="M397 85 L407 63"
                            stroke="#3978DB"
                            stroke-width="5"
                            stroke-linecap="round"
                        />

                        <path
                            d="M414 107 L436 94"
                            stroke="#3978DB"
                            stroke-width="5"
                            stroke-linecap="round"
                        />

                        <path
                            d="M418 129 L442 129"
                            stroke="#3978DB"
                            stroke-width="5"
                            stroke-linecap="round"
                        />

                        <path
                            d="M360 300 Q347 264 370 234"
                            fill="none"
                            stroke="#59BC44"
                            stroke-width="4"
                        />

                        <path
                            d="M366 263 Q326 265 326 230 Q359 232 366 263"
                            fill="#83D67A"
                        />

                        <path
                            d="M369 247 Q374 214 406 213 Q404 241 369 247"
                            fill="#59BC44"
                        />

                        <path
                            d="M359 292 Q319 294 310 266 Q344 263 359 292"
                            fill="#A6E49D"
                        />

                    </svg>

                </div>

            </div>

        </div>

    </section>

</main>

<?php get_footer(); ?>
