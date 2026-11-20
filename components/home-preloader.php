<?php
/**
 * LiHi Homepage Preloader
 */
?>

<div
    class="lihi-preloader"
    id="lihiPreloader"
    role="status"
    aria-label="Charging up homepage"
>
    <div class="lihi-preloader__inner">

        <div class="lihi-preloader__label">
            CHARGING UP
        </div>

        <div
            class="lihi-preloader__counter"
            id="lihiPreloaderCounter"
            aria-hidden="true"
        >
            <div
                class="lihi-preloader__layer lihi-preloader__outline"
                id="lihiPreloaderOutline"
            ></div>

            <div
                class="lihi-preloader__layer lihi-preloader__fill"
                id="lihiPreloaderFill"
            ></div>
        </div>

    </div>
</div>