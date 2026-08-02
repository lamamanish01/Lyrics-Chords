document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('lyrics-container');
    if (!container) {
        console.warn('Lyrics container not found.');
        return;
    }

    // ----- Font size with display -----
    const MIN_FONT = 10, MAX_FONT = 40, DEFAULT_FONT = 16;
    let currentFont = parseInt(localStorage.getItem('lyricsFontSize')) || DEFAULT_FONT;

    // Get the display element
    const fontSizeDisplay = document.getElementById('fontSizeDisplay');
    if (fontSizeDisplay) {
        fontSizeDisplay.textContent = currentFont;
    }

    function setFontSize(size) {
        size = Math.min(Math.max(size, MIN_FONT), MAX_FONT);
        currentFont = size;
        container.style.fontSize = size + 'px';
        localStorage.setItem('lyricsFontSize', size);
        // Update the display
        if (fontSizeDisplay) {
            fontSizeDisplay.textContent = size;
        }
    }

    const incBtn = document.getElementById('fontIncrease');
    const decBtn = document.getElementById('fontDecrease');
    const resetBtn = document.getElementById('fontReset');

    if (incBtn) incBtn.addEventListener('click', () => setFontSize(currentFont + 2));
    if (decBtn) decBtn.addEventListener('click', () => setFontSize(currentFont - 2));
    if (resetBtn) resetBtn.addEventListener('click', () => setFontSize(DEFAULT_FONT));
    setFontSize(currentFont);

    // ----- Chord toggle -----
    const toggleBtn = document.getElementById('chordToggle');
    const toggleLabel = document.getElementById('chordToggleLabel');
    if (!toggleBtn || !toggleLabel) {
        console.warn('Chord toggle elements not found.');
        return;
    }

    let chordsVisible = localStorage.getItem('chordsVisible') !== 'false';

    function setChordsVisible(visible) {
        chordsVisible = visible;
        container.classList.toggle('hide-chords', !visible);
        toggleLabel.textContent = visible ? 'Hide Chords' : 'Show Chords';
        localStorage.setItem('chordsVisible', visible);
        const icon = toggleBtn.querySelector('i');
        if (icon) {
            icon.className = visible ? 'fas fa-music' : 'fas fa-music-slash';
        }
    }

    setChordsVisible(chordsVisible);
    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        setChordsVisible(!chordsVisible);
    });
});
