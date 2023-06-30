// Obtener el formulario por su ID
const registrationForm = document.getElementById('registrationForm');

// Agregar un evento de envío de formulario
registrationForm.addEventListener('submit', (event) => {
  // Prevenir el comportamiento por defecto de envío de formulario
  event.preventDefault();

  // Obtener los datos del formulario
  const firstName = document.querySelector('input[name="firstName"]').value;
  const lastName = document.querySelector('input[name="lastName"]').value;
  const email = document.querySelector('input[name="email"]').value;
  const password = document.querySelector('input[name="password"]').value;
  const repeatPassword = document.querySelector('input[name="repeatPassword"]').value;

  // Crear un objeto FormData con los datos del formulario
  const formData = new FormData();
  formData.append('firstName', firstName);
  formData.append('lastName', lastName);
  formData.append('email', email);
  formData.append('password', password);
  formData.append('repeatPassword', repeatPassword);

  // Crear un objeto XMLHttpRequest
  const xhr = new XMLHttpRequest();

  // Configurar la solicitud
  xhr.open('POST', '../Conexion/registrar_usuario.php');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

  // Agregar un controlador de eventos para la respuesta de la solicitud
  xhr.addEventListener('load', () => {
    if (xhr.status === 200) {
      // Mostrar un mensaje de éxito
      alert(xhr.responseText);
      // Redirigir a la página de inicio de sesión
      window.location.href = '../Views/login.html';
    } else {
      // Mostrar un mensaje de error
      alert('Ocurrió un error al registrar el usuario. Por favor, inténtalo de nuevo más tarde.');
    }
  });

  // Enviar la solicitud
  xhr.send(formData);
});
