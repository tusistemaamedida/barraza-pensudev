

function notificacionAlerta(texto){
    Toastify({
        text: texto,
        duration: 3000,
        escapeMarkup:true,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
          background: "linear-gradient(to right, #b01300, #d86f53)",
        },
        onClick: function(){} // Callback after click
      }).showToast();
}

function notificacionExito(texto){
    Toastify({
        text: texto,
        duration: 3000,
        escapeMarkup:true,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
          background: "linear-gradient(to right,  #00b09b, #96c93d)",
        },
        onClick: function(){} // Callback after click
      }).showToast();
}

function show_spinner () {
    document.getElementById("spinner").classList.add("show");
  }
  function hide_spinner () {
    document.getElementById("spinner").classList.remove("show");
  }
