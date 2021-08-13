import Sortable from 'sortablejs';

let el = document.querySelector('#process_Steps')
if(el){
    let sortable = Sortable.create(el, {
        // handle: '.handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        filter: 'button',
        onEnd: (/**Event*/evt) => {
            reorderingSteps();
        }
    })

    let reorderingSteps = () => {
        let i = 1

        let steps = el.querySelectorAll('fieldset.mb-3')

        steps.forEach(e =>
            e.querySelectorAll('.weight')['0'].value = i++
        )
    }

    document.addEventListener("change", reorderingSteps)
    el.addEventListener("change", reorderingSteps)

    reorderingSteps()
}

