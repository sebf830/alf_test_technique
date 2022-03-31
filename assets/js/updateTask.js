import $ from 'jquery';

document.querySelectorAll(".update").forEach(function (edit) {
    edit.addEventListener('click', function () {
        edit.classList.add('d-none')
        edit.nextElementSibling.classList.remove("d-none") // </a>

        // <span>
        edit.previousElementSibling.setAttribute('contenteditable', 'true')
        edit.previousElementSibling.classList.add('editable')
    });
});

document.querySelectorAll(".updateTask").forEach(function (sendDatasButton) {
    sendDatasButton.addEventListener('click', function () {

        let taskId = sendDatasButton.dataset.idtask;
        let taskContent = sendDatasButton.parentElement.textContent.trim()

        sendDatasButton.classList.add('d-none')
        sendDatasButton.previousElementSibling.classList.remove("d-none")

        if (taskId !== "" && taskContent !== "")
            postEditedTask(taskId, taskContent)

        sendDatasButton.previousElementSibling.previousElementSibling.setAttribute('contenteditable', 'false')
        sendDatasButton.previousElementSibling.previousElementSibling.classList.remove('editable')
    });
});

function postEditedTask(id, title) {
    var data = { 'id': id, 'title': title }

    $.ajax({
        method: "post",
        url: '/task/update',
        headers: { 'Content-Type': 'application/json' },
        dataType: "json",
        data: JSON.stringify(data),
        success: function (response) {
            console.log(response)
        }
    })
}
