(function () {
  /* ---- Tweak the feel here ---- */
  var DURATION    = 1000;  // ms per slide change (arrow buttons / keys)
  var EXIT_SCALE  = 0.14;  // how much the leaving card shrinks (0.14 = down to 86%)
  var EXIT_FOLD   = 24;    // degrees the leaving card folds away in 3D (right edge swings back)
  var PERSPECTIVE = 1200;  // px; lower = stronger 3D depth
  var EXIT_FADE   = 1.4;   // fade curve of the leaving card: higher = stays visible longer, then drops
  var EXIT_SHIFT  = 28;    // px the leaving card drifts inward while it folds

  /* ---- Inertia & bounce ---- */
  var FRICTION       = 1.9;  // 1/s. LOWER = glides further. (hard flick ≈ 6 cards, medium ≈ 3, slow = 1)
  var FLING_GAIN     = 1.0;  // multiplies your flick speed. >1 = flicks travel further
  var MAX_FLING      = 14;   // cards/s cap, so a spiky trackpad can never launch it across everything
  var SNAP_SPEED     = 3.2;  // cards/s. Below this speed the "magnet" to the nearest card fades in
  var SNAP_STIFF     = 90;   // magnet strength
  var SNAP_DAMP      = 17;   // magnet damping (≈19 = no overshoot, lower = tiny settle wobble)
  var EDGE_STIFF     = 160;  // how hard the ends push back
  var EDGE_DAMP      = 15;   // lower = bouncier ends, higher = firmer
  var RUBBER         = 0.8;  // cards: the most you can stretch past an end while dragging
  var DRAG_THRESHOLD = 0.18; // drag this fraction of a card and let go → it moves on to the next card
  var VELOCITY_WINDOW = 100; // ms of recent movement used to measure your flick speed
  var WHEEL          = true; // horizontal trackpad / mouse-wheel scrolling uses the same physics
  /* ----------------------------- */

  var EASE = function (t) { // easeInOutCubic – arrow buttons
    return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
  };

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function init(root) {
    var viewport = root.querySelector('.lihi-home-carousel__viewport');
    var track    = root.querySelector('.lihi-home-carousel__track');
    var cards    = Array.prototype.slice.call(track.children);
    var prev     = root.querySelector('.lihi-home-carousel__prev');
    var next     = root.querySelector('.lihi-home-carousel__next');
    if (!cards.length) return;

    var p = 0;            // current (fractional) position, in cards
    var target = 0;       // index we are heading to
    var maxIndex = 0;
    var step = 0;
    var raf = null;

    var dragging = false, moved = false, startX = 0, startP = 0, pointerId = null;
    var samples = [];     // recent {x, t} pointer samples for a stable speed reading
    var vel = 0, snapDir = 0;   // physics state (cards/s, release direction)
    var wheelTimer = null, wheelStartP = 0, wheelActive = false;

    var clamp = function (v, a, b) { return Math.min(Math.max(v, a), b); };

    // Cards must be solid so the top card hides the card behind it.
    // The look comes from CSS; this is only a fallback if no background is set.
    cards.forEach(function (c) {
      var cb = getComputedStyle(c).backgroundColor;
      if (cb === 'rgba(0, 0, 0, 0)' || cb === 'transparent') c.style.background = '#fff';
      c.style.position = 'relative';
      c.style.willChange = 'transform, opacity';
      c.style.transformOrigin = '0% 50%'; // fold hinges on the card's left edge
    });

    /* Draw one frame for a given fractional position */
    function render(pos) {
      track.style.transform = 'translate3d(' + (-pos * step) + 'px,0,0)';

      for (var i = 0; i < cards.length; i++) {
        var d = i - pos;
        var c = cards[i];

        if (d >= 0) {
          // upcoming / current cards: normal track movement, sit on top
          c.style.transform = 'none';
          c.style.opacity = '1';
          c.style.zIndex = '2';
          c.style.pointerEvents = '';
          c.style.visibility = '';
        } else {
          // leaving card: pinned in the first slot, folds back in 3D + shrinks + fades
          var a = Math.min(-d, 1);
          var tx = -d * step + EXIT_SHIFT * a; // cancels the track movement; small drift inward
          var op = clamp(1 - Math.pow(a, EXIT_FADE), 0, 1);
          c.style.transform =
            'translate3d(' + tx + 'px,0,0) ' +
            'perspective(' + PERSPECTIVE + 'px) ' +
            'scale(' + (1 - EXIT_SCALE * a) + ') ' +
            'rotateY(' + (EXIT_FOLD * a) + 'deg)';
          c.style.opacity = String(op);
          c.style.zIndex = '1';
          c.style.pointerEvents = 'none';
          c.style.visibility = op === 0 ? 'hidden' : '';
        }
      }
      p = pos;
    }

    function updateUI() {
      var idx = Math.round(target);
      prev.disabled = idx <= 0;
      next.disabled = idx >= maxIndex;
    }

    function stopMotion() { cancelAnimationFrame(raf); raf = null; vel = 0; }

    /* ---------- arrow buttons / keys: timed ease (your original feel) ---------- */
    function animateTo(i) {
      target = clamp(i, 0, maxIndex);
      updateUI();
      stopMotion();

      if (reduceMotion) { render(target); return; }

      var from = p;
      var dist = Math.abs(target - from);
      if (dist < 0.001) { render(target); return; }

      var dur = DURATION + Math.min(dist - 1, 3) * 150 * (dist > 1 ? 1 : 0);
      var t0 = performance.now();

      (function tick(now) {
        var t = clamp((now - t0) / dur, 0, 1);
        render(from + (target - from) * EASE(t));
        if (t < 1) raf = requestAnimationFrame(tick);
      })(t0);
    }

    /* ---------- swipe release: inertia + magnet + bounce ---------- */

    // Which card the magnet pulls toward. Leans in the direction you were going,
    // so dragging ~18% of a card is enough to move on.
    function snapTarget(x, v) {
      var look = x + v * 0.1;
      var t;
      if (snapDir > 0)      t = Math.floor(look + 1 - DRAG_THRESHOLD);
      else if (snapDir < 0) t = Math.ceil(look - 1 + DRAG_THRESHOLD);
      else                  t = Math.round(look);
      return clamp(t, 0, maxIndex);
    }

    var H = 1 / 120; // fixed physics step (s) → identical feel on 60 / 120 / 144 Hz screens

    function startPhysics(v0) {
      cancelAnimationFrame(raf);
      var pos = p, v = v0, acc = 0, last = performance.now(), born = last;

      target = snapTarget(pos + v / FRICTION, 0);
      updateUI();

      if (reduceMotion) { render(target); vel = 0; return; }

      (function tick(now) {
        acc += Math.min((now - last) / 1000, 0.05);
        last = now;

        var T = snapTarget(pos, v), out = 0, settled = false;

        while (acc >= H) {
          acc -= H;
          out = pos < 0 ? pos : pos > maxIndex ? pos - maxIndex : 0;
          T = snapTarget(pos, v);
          var a;
          if (out) {
            // past an end: elastic spring back (underdamped = bounce)
            a = -EDGE_STIFF * out - EDGE_DAMP * v;
          } else {
            // magnet fades in as the glide slows down
            var s = clamp(1 - Math.abs(v) / SNAP_SPEED, 0, 1);
            s = s * s * (3 - 2 * s);
            a = -s * SNAP_STIFF * (pos - T) - s * SNAP_DAMP * v - FRICTION * v;
          }
          v += a * H;
          pos += v * H;
          if (pos > maxIndex + 1) { pos = maxIndex + 1; if (v > 0) v = 0; } // hard stop on the stretch
          if (pos < -1)           { pos = -1;           if (v < 0) v = 0; }
        }

        render(pos);
        vel = v;

        if (target !== T) { target = T; updateUI(); }

        out = pos < 0 ? pos : pos > maxIndex ? pos - maxIndex : 0;
        settled = !out && Math.abs(v) < 0.02 && Math.abs(pos - T) < 0.0015;
        if (settled || now - born > 6000) {
          render(T); vel = 0; snapDir = 0; target = T; updateUI(); raf = null;
          return;
        }
        raf = requestAnimationFrame(tick);
      })(last);
    }

    function measure() {
      var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
      // Use layout width, NOT getBoundingClientRect(): the first card may be
      // scaled/folded by the exit animation, which would shrink the measurement.
      step = parseFloat(getComputedStyle(cards[0]).width) + gap;

      // how many full cards fit between the track start and the screen edge
      var avail = window.innerWidth - viewport.getBoundingClientRect().left;
      var visible = Math.max(1, Math.floor((avail + gap) / step));
      maxIndex = Math.max(0, cards.length - visible);

      stopMotion();
      target = clamp(Math.round(target), 0, maxIndex);
      render(target);
      updateUI();
    }

    prev.addEventListener('click', function () { animateTo(Math.round(target) - 1); });
    next.addEventListener('click', function () { animateTo(Math.round(target) + 1); });

    viewport.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft')  { e.preventDefault(); animateTo(Math.round(target) - 1); }
      if (e.key === 'ArrowRight') { e.preventDefault(); animateTo(Math.round(target) + 1); }
    });

    /* Finger speed over the last VELOCITY_WINDOW ms, px/ms (+ = moving right).
       A time WINDOW (not the last two events, which can be <1 ms apart and give absurd speeds);
       0 if the finger stopped before letting go. */
    function releaseVelocity(now) {
      var recent = samples.filter(function (s) { return now - s.t <= VELOCITY_WINDOW; });
      if (recent.length < 2) return 0;
      var a = recent[0], b = recent[recent.length - 1];
      return clamp((b.x - a.x) / Math.max(b.t - a.t, 16), -6, 6);
    }

    /* Progressive resistance past the ends: the further you pull, the heavier it gets */
    function rubber(pos) {
      if (pos < 0)        return -RUBBER * (1 - 1 / (-pos / RUBBER * 0.9 + 1));
      if (pos > maxIndex) return maxIndex + RUBBER * (1 - 1 / ((pos - maxIndex) / RUBBER * 0.9 + 1));
      return pos;
    }

    /* Drag / swipe — drives the same effect live */
    viewport.addEventListener('pointerdown', function (e) {
      if (e.pointerType === 'mouse' && e.button !== 0) return;
      // Only start a drag on a card that is still in view.
      // Empty space and the area of cards already scrolled away do nothing.
      var hit = e.target.closest ? e.target.closest('.lihi-home-carousel__card') : null;
      if (!hit || cards.indexOf(hit) - p < -0.05) return;
      stopMotion();                       // grabbing a gliding carousel stops it, like a real object
      clearTimeout(wheelTimer); wheelActive = false;
      dragging = true; moved = false;
      startX = e.clientX;
      samples = [{ x: e.clientX, t: performance.now() }];
      startP = p;
      pointerId = e.pointerId;
    });

    viewport.addEventListener('pointermove', function (e) {
      if (!dragging) return;
      var dx = e.clientX - startX;
      if (!moved && Math.abs(dx) > 5) {
        moved = true;
        viewport.classList.add('is-dragging');
        viewport.setPointerCapture(pointerId);
      }
      if (!moved) return;

      var now = performance.now();
      samples.push({ x: e.clientX, t: now });
      while (samples.length > 2 && now - samples[0].t > VELOCITY_WINDOW * 2) samples.shift();

      render(rubber(startP - dx / step));
    });

    function endDrag() {
      if (!dragging) return;
      dragging = false;
      viewport.classList.remove('is-dragging');
      if (!moved) return;

      var v = releaseVelocity(performance.now());                    // px/ms
      var v0 = clamp(-v * 1000 / step * FLING_GAIN, -MAX_FLING, MAX_FLING); // cards/s, + = forward
      if (Math.abs(v0) < 0.15) v0 = 0;

      var delta = p - startP;                                        // cards dragged, + = forward
      snapDir = Math.abs(v0) > 0.8 ? (v0 > 0 ? 1 : -1)
              : Math.abs(delta) > 0.02 ? (delta > 0 ? 1 : -1) : 0;

      startPhysics(v0);
    }
    viewport.addEventListener('pointerup', endDrag);
    viewport.addEventListener('pointercancel', endDrag);

    // A drag must not open the product link
    viewport.addEventListener('click', function (e) {
      if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; }
    }, true);

    /* Horizontal trackpad / wheel scrolling: follow the wheel, then magnet to the nearest card.
       (Trackpads already send their own momentum as wheel events, so we just snap when it stops.) */
    if (WHEEL) {
      viewport.addEventListener('wheel', function (e) {
        if (Math.abs(e.deltaX) <= Math.abs(e.deltaY)) return;        // vertical = normal page scroll
        e.preventDefault();
        if (!wheelActive) { stopMotion(); wheelActive = true; wheelStartP = p; }
        var pos = p + e.deltaX / step;
        if (pos > -0.0001 && pos < maxIndex + 0.0001) { render(pos); }
        else render(rubber(pos < 0 ? pos : pos));
        clearTimeout(wheelTimer);
        wheelTimer = setTimeout(function () {
          wheelActive = false;
          var d = p - wheelStartP;
          snapDir = Math.abs(d) > 0.02 ? (d > 0 ? 1 : -1) : 0;
          startPhysics(0);
        }, 110);
      }, { passive: false });
    }

    var resizeRaf;
    window.addEventListener('resize', function () {
      cancelAnimationFrame(resizeRaf);
      resizeRaf = requestAnimationFrame(measure);
    });
    window.addEventListener('load', measure);

    measure();
  }

  document.querySelectorAll('[data-lihi-carousel]').forEach(init);
})();