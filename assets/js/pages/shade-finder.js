document.querySelectorAll('.swatch').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.swatch').forEach(b => { b.classList.remove('selected'); b.setAttribute('aria-pressed', 'false'); });
        btn.classList.add('selected');
        btn.setAttribute('aria-pressed', 'true');
        const tone = btn.dataset.tone;
        document.getElementById('skin_tone_input').value = tone;
        document.getElementById('tone-label').textContent = 'Selected: ' + tone.charAt(0).toUpperCase() + tone.slice(1);
        document.getElementById('find-btn').disabled = false;
    });
});
