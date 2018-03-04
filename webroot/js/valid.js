window.onload = function (){
    var inputs = document.getElementsByClassName("input-register");
    for (var i = 0; i < inputs.length; i++){
        inputs[i].addEventListener('focus', focusInput, false);
        inputs[i].addEventListener('blur', blurInput, false);
    }
    document.forms[0].addEventListener('submit', formValid, false);
}

function focusInput(e){
    var parent = this.parentElement;

    var children = parent.children,
        label = children[0],
        input = children[1],
        error = children[2];

    error.innerText = '';

    if (input.classList.contains('input-red'))
        input.classList.remove("input-red");

    if (!label.classList.contains('place-top'))
        label.classList.add("place-top");

}

function blurInput(e)
{
    var parent = this.parentElement;

    var children = parent.children,
        label = children[0],
        input = children[1],
        error = children[2];

    if (label.classList.contains('place-top') && (this.value=="")){
        label.classList.remove("place-top");
    }

    if (this.value == ""){
        addRedStyle(input, error, "Field is reqiered!");
        return;
    }

    var errorMessages = {
        login: "Login must be 5-10 symbols (a-z A-Z 0-9 _ . -)",
        email: "Email is wrong",
        password: "Password must be 5-10 symbols",
        password_second: "Passwords must be the same",
    }
    var messageName = input.name;

    if (input.name == "password_second"){
        var inputPsw = document.getElementById("psw").value;
        if (inputPsw != input.value){
            addRedStyle(input, error, errorMessages[messageName]);
            return;
        } else {
            messageName = "password";
        }
    }

    var funcName = input.dataset.func;
    if (!eval( funcName )(input.value)){

        addRedStyle(input, error, errorMessages[messageName]);
        return;
    }

    formValid();
}

function validLogin(login)
{
    var pattern = /\b[a-zA-Z0-9._\-]{5,10}\b/i;
    return pattern.test(login);
}

function validEmail(email)
{
    var pattern = /\b[a-z0-9._]+@[a-z0-9.-]+\.[a-z]{2,4}\b/i;
    return pattern.test(email);
}

function validPsw(psw)
{
    var pattern = /\b.{5,10}\b/i;
    return pattern.test(psw);
}

function addRedStyle(input, error, text)
{
    input.classList.add("input-red");
    error.innerText = text;
    document.getElementById("btn").disabled = true;
}

function formValid()
{
    debugger;
    var valid = true;
    var parent, error;
    var inputs = document.getElementsByClassName("input-register");
    for (var i = 0; i < inputs.length; i++){
        parent = inputs[i].parentElement;
        error = parent.lastChild.innerText;
        if (inputs[i].value == "" || error != undefined){
            valid = false;
            break;
        }
    }

    document.getElementById("btn").disabled = !valid;

    return valid;
}