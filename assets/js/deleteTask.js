
let cancels = document.querySelectorAll(".cancel_delete");
let buttons = document.querySelectorAll(".button");

buttons.forEach(function (button) {
    let id = button.dataset.id
    button.addEventListener("click", function () {
        document.querySelector(`.modal${id}`).style.display = "Block";
    });
});

cancels.forEach(function (cancel) {
    cancel.addEventListener("click", function () {
        cancel.parentNode.parentNode.style.display = "none";
    });
});
