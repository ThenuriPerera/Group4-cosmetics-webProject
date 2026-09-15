document.addEventListener("DOMContentLoaded", function () {
    const toneButtons = document.querySelectorAll(".tone-square");
    const toneInput = document.getElementById("skin_tone_input");
    const toneLabel = document.getElementById("tone-label");
    const findButton = document.getElementById("find-btn");

    toneButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            toneButtons.forEach(function (item) {
                item.classList.remove("selected");
                item.setAttribute("aria-pressed", "false");
            });

            button.classList.add("selected");
            button.setAttribute("aria-pressed", "true");

            toneInput.value = button.dataset.tone;
            toneLabel.textContent = "Skin tone selected.";
            findButton.disabled = false;
        });
    });
});