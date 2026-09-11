const steps = document.querySelectorAll('.quiz-step');
let current = 0;
function showStep(i) {
    steps.forEach((s, idx) => s.hidden = idx !== i);
}
document.querySelectorAll('.next-btn').forEach(btn => {
    btn.addEventListener('click', () => { current = Math.min(current + 1, steps.length - 1); showStep(current); });
});
document.querySelectorAll('.prev-btn').forEach(btn => {
    btn.addEventListener('click', () => { current = Math.max(current - 1, 0); showStep(current); });
});
