const container = document.getElementById('container');
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');

if (signUpButton) {
  signUpButton.addEventListener('click', () => {
    container.classList.add('right-panel-active');
  });
}

if (signInButton) {
  signInButton.addEventListener('click', () => {
    container.classList.remove('right-panel-active');
  });
}

function togglePassword(icono) {
    // 1. Buscamos el input que está en el mismo contenedor que el icono
    const input = icono.parentElement.querySelector('input');
    
    if (input) {
        if (input.type === "password") {
            input.type = "text";
            icono.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = "password";
            icono.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
}