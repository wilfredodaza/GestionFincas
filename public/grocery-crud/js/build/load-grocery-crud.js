
$(() => {
  jQuery(document).ready(function() {
      jQuery('.gc-container').groceryCrud({
          callbackAfterUpdate: function () {
              replace_colors()
          },
          callbackAfterInsert: function () {
              replace_colors()
          },
      });
  });
});

function replace_colors(){
  if($('input[name="primary_color"]').val() != undefined){
      var primary     = $('input[name="primary_color"]').val().trim();
      var secundary   = $('input[name="secundary_color"]').val().trim();
      document.documentElement.style.setProperty('--primary-color', `#${primary == '' ? '8e24aa' : primary}`);
      document.documentElement.style.setProperty('--secondary-color', `#${secundary == '' ? 'ff6e40' : secundary}`);
      document.documentElement.style.setProperty('--primary-rgb', hexToRgb(`${primary == '' ? '8e24aa' : primary}`));
      document.documentElement.style.setProperty('--secondary-rgb', hexToRgb(`${secundary == '' ? 'ff6e40' : secundary}`));

      document.documentElement.style.setProperty('--primary-ligth', lightenColor(`${primary == '' ? '8e24aa' : primary}`, 90));
      document.documentElement.style.setProperty('--primary-ligth-2', lightenColor(`${primary == '' ? '8e24aa' : primary}`, 80));
      document.documentElement.style.setProperty('--primary-ligth-3', lightenColor(`${primary == '' ? '8e24aa' : primary}`, 70));

      document.documentElement.style.setProperty('--primary-darken', darkenColor(`${primary == '' ? '8e24aa' : primary}`, 50));
      document.documentElement.style.setProperty('--primary-darken-2', darkenColor(`${primary == '' ? '8e24aa' : primary}`, 60));
      document.documentElement.style.setProperty('--primary-darken-3', darkenColor(`${primary == '' ? '8e24aa' : primary}`, 70));
      document.documentElement.style.setProperty('--primary-darken-4', darkenColor(`${primary == '' ? '8e24aa' : primary}`, 80));
  }
}

function hexToRgb(hex) {
  var bigint = parseInt(hex, 16);
  var r = (bigint >> 16) & 255;
  var g = (bigint >> 8) & 255;
  var b = bigint & 255;
  return `${r}, ${g}, ${b}`;
}

// Función para oscurecer un color
function darkenColor(hex, percent) {
  // Quitar el carácter '#' si está presente
  hex = hex.replace('#', '');

  // Convertir el valor HEX a RGB
  let r = parseInt(hex.substring(0, 2), 16);
  let g = parseInt(hex.substring(2, 4), 16);
  let b = parseInt(hex.substring(4, 6), 16);

  // Interpolar cada componente RGB hacia negro (0, 0, 0)
  r = Math.round(r * (1 - percent / 100));
  g = Math.round(g * (1 - percent / 100));
  b = Math.round(b * (1 - percent / 100));

  // Convertir de vuelta a HEX y retornar el nuevo color
  return `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase()}`;
}

// Función para aclarar un color
function lightenColor(hex, percent) {
  // Quitar el carácter '#' si está presente
  hex = hex.replace('#', '');

  // Convertir el valor HEX a RGB
  let r = parseInt(hex.substring(0, 2), 16);
  let g = parseInt(hex.substring(2, 4), 16);
  let b = parseInt(hex.substring(4, 6), 16);

  // Interpolar cada componente RGB hacia blanco (255, 255, 255)
  r = Math.round(r + (255 - r) * (percent / 100));
  g = Math.round(g + (255 - g) * (percent / 100));
  b = Math.round(b + (255 - b) * (percent / 100));

  // Convertir de vuelta a HEX y retornar el nuevo color
  return `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase()}`;
}