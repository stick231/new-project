document.getElementById("link").addEventListener("click", () => {
    fetch("/register?register=false", {
        method: "GET",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    })
    .then(response => {
        if (response.ok) {
            console.log("Перенаправление на регистрацию");
            setTimeout(() => {
                window.location = "/register";
            }, 500);
        } else {
            console.error("Ошибка при перенаправлении:", response.status);
        }
    })
    .catch(error => {
        console.error("Ошибка при выполнении запроса:", error);
    });
});

function CheckInp(){
    const inpUsername = document.getElementById("username");
    const inpPassword = document.getElementById("password");


    if(inpUsername.value == null || inpUsername.value == ""){
        alert("Введите имя пользователя")
        return false;
    }

    if(inpPassword.value == null || inpPassword.value == ""){
        alert("Введите пароль")
        return false;
    }

    return true;
}

document.getElementById("submit").addEventListener("click", (event) =>{
    event.preventDefault();
    
    if (!CheckInp()) {
        return;
    }

    const form = document.getElementById('formAuth');
    const formData = new FormData(form);
    formData.append('action', 'auth');

    fetch('/auth', {
        method: "POST",
        body: formData
    })
    .then(response =>{
        if (!response.ok) {
            throw new Error('Ошибка сети: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        if (!data.success) {
            showError(data.message); 
        }
        else{
            window.location.reload();
        }
    })

    .catch(error => {
        console.error(error)
    })
})

function showError(message) {
    const errorDiv = document.getElementById('error-message'); 
    errorDiv.textContent = message; 
    errorDiv.style.display = 'block'; 
}