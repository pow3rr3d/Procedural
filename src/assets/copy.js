const btn = document.querySelector(".btn.copyBtn")
const copyText = document.querySelector(".token")
let fa = document.querySelector('.far.fa-copy')
let fa2 = document.createElement("i")
fa2.classList.add("fa-check")
fa2.classList.add("fas")

btn.addEventListener("click", function () {
    navigator.clipboard.writeText(copyText.innerHTML);
    fa.remove()
    btn.appendChild(fa2)
    window.setTimeout(() =>{
        fa2.remove()
        btn.appendChild(fa)
    }, 1000)
})
