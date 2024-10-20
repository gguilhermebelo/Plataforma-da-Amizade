function handleSelectChange(selectElement, e) {
    const valorSelecionado = selectElement.value;
    const perguntaid = selectElement.id;

    if (valorSelecionado != "NÃO" ) {
        const hiddenInput = document.getElementById(`hidden-esta-conforme-${perguntaid}`);
        hiddenInput.value = valorSelecionado;

        console.log(document.getElementById(`form-${perguntaid}`))
        document.getElementById(`form-${perguntaid}`).submit();
    }

    if (valorSelecionado == "NÃO") {
        toggleDisplayNone(document.querySelector(".segundo-form"));

        document.querySelector("#id-pergunta").value = perguntaid;
        document.querySelector("#id-conforme").value = valorSelecionado;
    }
}

function toggleDisplayNone(element) {
    if (element.target && element.target.classList.contains("segundo-form")) {
        console.log(element.target.classList.toggle("absolute"))
        console.log(element.target.classList.toggle("none"))
        console.log(element.target)

        if (element.target.classList.contains("none") ) {
            window.location = 'checkListProjeto.php'
        }
    } else {
        console.log(element.classList.toggle("none"))
        console.log(element.classList.toggle("absolute"))
        console.log(element)

        if (element.classList.contains("none")) {
            window.location = 'checkListProjeto.php'
        }
    }

}    
