const swiper = new Swiper('.swiper', {
  // Optional parameters
  allowTouchMove: true,    
  loop: true,
  effect: "fade",
  autoplay:true,

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
    clickable: true,
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  
});

function eliminarUsuario(id_usuario) {
  // Confirmar que se quiere eliminar el usuario
  if (confirm("¿Está seguro de que desea eliminar este usuario?")) {
    // Realizar petición AJAX al archivo eliminar_user.php
    $.ajax({
      type: "POST",
      url: "../ferreteria/eliminar_user.php",
      data: { id_usuario: id_usuario },
      success: function (response) {
        // Si la petición se completó correctamente, recargar la página
        location.reload();
      },
      error: function (xhr, status, error) {
        console.log(xhr);
        console.log(status);
        console.log(error);
      },
    });
  }
}
