const form = document.getElementById('form-register');

form.addEventListener('submit', (e) => {
  e.preventDefault(); // Evitar el envío por defecto del formulario

  // Recoger los datos del formulario
  const formData = {
    name: document.getElementById('register-name').value,
    email: document.getElementById('register-email').value,
    password: document.getElementById('register-password').value,
    confirmPassword: document.getElementById('register-password-repeat').value,
    registrationDate: new Date().toISOString() // Fecha de registro
  };

  // Validación de contraseñas
  if (formData.password !== formData.confirmPassword) {
    alert('Las contraseñas no coinciden');
    return;
  }

  fetch('http://localhost/proyectoBiblioteca/backend/add-user.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(formData) // Enviar los datos en formato JSON
  })
  .then(response => response.json())
  .then(data => {
    console.log('Success:', data);
    alert('Usuario registrado correctamente');
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Hubo un problema al registrar al usuario');
  });
});
