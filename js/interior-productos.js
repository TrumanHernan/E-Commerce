function cambiarCantidad(valor) {
  const cantidadInput = document.getElementById('cantidad');
  let cantidad = parseInt(cantidadInput.value) + valor;

  if (cantidad < 1) cantidad = 1;
  if (cantidad > 99) cantidad = 99;

  cantidadInput.value = cantidad;
}

function cambiarProducto(imagen, nombre, precio) {
  document.getElementById('imagenProducto').src = '/asset/img/' + imagen;
  document.getElementById('nombreProducto').textContent = nombre;
  document.getElementById('precioProducto').textContent = precio;
  document.getElementById('cantidad').value = 1;

  window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.getElementById('cantidad').addEventListener('input', function() {
  let valor = parseInt(this.value);
  if (isNaN(valor) || valor < 1) this.value = 1;
  if (valor > 99) this.value = 99;
});
