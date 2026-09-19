document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('#quiz-form');

    if (!form) {
        return;
    }


    const steps = Array.from(
        form.querySelectorAll('.quiz-step')
    );

    const progressFill = document.querySelector(
        '#quiz-progress-fill'
    );

    const progressLabel = document.querySelector(
        '#quiz-progress-label'
    );

    const submitButton = document.querySelector(
        '#quiz-submit'
    );


    if (!steps.length) {
        return;
    }


    let currentStep = 0;


    function validateCurrentStep() {

        const currentFields = steps[currentStep]
            .querySelectorAll(
                'input:not([type="hidden"]), select, textarea'
            );

        for (const field of currentFields) {

            if (!field.checkValidity()) {
                field.reportValidity();
                return false;
            }
        }

        return true;
    }


    function showStep(index) {

        if (index < 0 || index >= steps.length) {
            return;
        }

        currentStep = index;


        steps.forEach((step, stepIndex) => {

            const active = stepIndex === currentStep;

            step.hidden = !active;

            step.setAttribute(
                'aria-hidden',
                active ? 'false' : 'true'
            );
        });


        const currentNumber = currentStep + 1;

        if (progressLabel) {

            progressLabel.textContent =
                `Step ${currentNumber} of ${steps.length}`;
        }


        if (progressFill) {

            const percentage =
                (currentNumber / steps.length) * 100;

            progressFill.style.width =
                `${percentage}%`;
        }


        const heading =
            steps[currentStep].querySelector('h3');

        if (heading) {

            heading.setAttribute(
                'tabindex',
                '-1'
            );

            heading.focus({
                preventScroll: true
            });
        }


        steps[currentStep].scrollIntoView({
            behavior: window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches
                ? 'auto'
                : 'smooth',

            block: 'start'
        });
    }


    form.querySelectorAll('.next-btn')
        .forEach(button => {

            button.addEventListener(
                'click',
                () => {

                    if (!validateCurrentStep()) {
                        return;
                    }

                    showStep(
                        Math.min(
                            currentStep + 1,
                            steps.length - 1
                        )
                    );
                }
            );
        });


    form.querySelectorAll('.prev-btn')
        .forEach(button => {

            button.addEventListener(
                'click',
                () => {

                    showStep(
                        Math.max(
                            currentStep - 1,
                            0
                        )
                    );
                }
            );
        });


    form.addEventListener(
        'submit',
        event => {

            if (!form.checkValidity()) {

                event.preventDefault();

                form.reportValidity();

                return;
            }


            if (submitButton) {

                submitButton.disabled = true;

                submitButton.textContent =
                    'Creating your routine...';
            }
        }
    );


    showStep(0);
});