const container = document.getElementById('container');
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const signUpMobile = document.getElementById('signUpMobile');
const signInMobile = document.getElementById('signInMobile');

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

if (signUpMobile) {
  signUpMobile.addEventListener('click', () => {
    container.classList.add('right-panel-active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

if (signInMobile) {
  signInMobile.addEventListener('click', () => {
    container.classList.remove('right-panel-active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

console.log("Archivo script.js cargado correctamente");

function togglePassword(icono) {
    // 1. Buscamos el input que está en el mismo contenedor que el icono
    const input = icono.parentElement.querySelector('input');
    
    if (input) {
        if (input.type === "password") {
            input.type = "text";
            icono.classList.replace('fa-eye', 'fa-eye-slash');
            console.log("Contraseña visible");
        } else {
            input.type = "password";
            icono.classList.replace('fa-eye-slash', 'fa-eye');
            console.log("Contraseña oculta");
        }
    } else {
        console.log("No se encontró el input");
    }
}