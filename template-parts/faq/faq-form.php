<!-- =====================================================
     FAQ — CONTACT FORM SECTION
===================================================== -->

<section class="faq-contact-section">

    <div class="wrap">

        <div class="faq-contact-header">

            <span class="faq-contact-label">CONTACT</span>

            <h2>Still have questions?</h2>

            <p>
                If your question isn't listed here, feel free to contact us
                or start a live chat with our team. We're happy to help!
            </p>

        </div>

        <div class="faq-contact-form">

            <form action="" method="post">

                <div class="faq-form-field">
                    <label for="faq-name">Full name</label>
                    <input
                        type="text"
                        id="faq-name"
                        name="name"
                        placeholder="Enter your name"
                        required
                    >
                </div>

                <div class="faq-form-field">
                    <label for="faq-email">Email address</label>
                    <input
                        type="email"
                        id="faq-email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >
                </div>

                <div class="faq-form-field">
                    <label for="faq-message">Message</label>
                    <textarea
                        id="faq-message"
                        name="message"
                        placeholder="Enter your message"
                        rows="4"
                        required
                    ></textarea>
                </div>

                <div class="faq-form-terms">
                    <label>
                        <input type="checkbox" name="terms" required>
                        <span>
                            I agree with the
                            <a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>">
                                terms and conditions
                            </a>
                        </span>
                    </label>
                </div>

                <button type="submit" class="faq-form-submit">
                    Submit
                </button>

            </form>

        </div>

    </div>

</section>