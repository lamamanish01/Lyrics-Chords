(function() {
    'use strict';
    const toggleBtn = document.getElementById('scrollToggle');
    const speedSlider = document.getElementById('scrollSpeed');
    const speedDisplay = document.getElementById('speedDisplay');
    const resetBtn = document.getElementById('scrollReset');
    const statusEl = document.getElementById('scrollStatus');

    if (!toggleBtn) return;

    let isPlaying = false;
    let speed = parseFloat(localStorage.getItem('autoScrollSpeed')) || 5.0;
    let animationId = null;
    let lastTimestamp = 0;
    const basePixelsPerSecond = 30;

    speedSlider.value = speed;
    speedDisplay.textContent = speed.toFixed(1);

    function toggleScroll() {
        if (isPlaying) pauseScroll();
        else playScroll();
    }

    function playScroll() {
        if (isPlaying) return;
        isPlaying = true;
        toggleBtn.innerHTML = '<i class="fas fa-pause"></i> Pause';
        toggleBtn.classList.remove('btn-success');
        toggleBtn.classList.add('btn-warning');
        statusEl.textContent = 'Scrolling...';
        lastTimestamp = performance.now();
        animationId = requestAnimationFrame(scrollStep);
    }

    function pauseScroll() {
        if (!isPlaying) return;
        isPlaying = false;
        if (animationId) cancelAnimationFrame(animationId);
        animationId = null;
        toggleBtn.innerHTML = '<i class="fas fa-play"></i> Play';
        toggleBtn.classList.remove('btn-warning');
        toggleBtn.classList.add('btn-success');
        statusEl.textContent = 'Paused';
    }

    function scrollStep(timestamp) {
        if (!isPlaying) return;
        const delta = timestamp - lastTimestamp;
        lastTimestamp = timestamp;
        const pixelsPerSecond = basePixelsPerSecond * speed;
        const pixelsToScroll = (pixelsPerSecond * delta) / 1000;
        window.scrollBy({ top: pixelsToScroll, behavior: 'auto' });
        animationId = requestAnimationFrame(scrollStep);
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (window.scrollY >= scrollHeight - 1) {
            pauseScroll();
            statusEl.textContent = 'End of page';
        }
    }

    function updateSpeed() {
        speed = parseFloat(speedSlider.value);
        speedDisplay.textContent = speed.toFixed(1);
        localStorage.setItem('autoScrollSpeed', speed);
    }

    function resetScroll() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    toggleBtn.addEventListener('click', toggleScroll);
    speedSlider.addEventListener('input', updateSpeed);
    resetBtn.addEventListener('click', resetScroll);

    document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        if (e.key === ' ' && !e.repeat) {
            e.preventDefault();
            toggleScroll();
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            let val = parseFloat(speedSlider.value) + 0.5;
            if (val > parseFloat(speedSlider.max)) val = parseFloat(speedSlider.max);
            speedSlider.value = val;
            updateSpeed();
        }
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            let val = parseFloat(speedSlider.value) - 0.5;
            if (val < parseFloat(speedSlider.min)) val = parseFloat(speedSlider.min);
            speedSlider.value = val;
            updateSpeed();
        }
    });

    window.addEventListener('beforeunload', function() {
        if (animationId) cancelAnimationFrame(animationId);
    });
})();
