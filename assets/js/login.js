
document.querySelectorAll('.label').forEach(function (lbl) {
    lbl.addEventListener('click', () => {
        lbl.previousElementSibling.style.height = "45px";
        lbl.previousElementSibling.classList.add("my-style");
        lbl.style.transform = "translateY(-45px)";
    })
});
