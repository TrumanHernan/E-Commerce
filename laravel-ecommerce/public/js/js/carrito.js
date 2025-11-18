function cambiarCantidad(button, cambio) {
  const cantidadSpan = button.parentElement.querySelector('.fw-bold');
  let cantidad = parseInt(cantidadSpan.textContent);
  cantidad += cambio;

  if (cantidad < 1) cantidad = 1;

  cantidadSpan.textContent = cantidad;
  actualizarTotales();
}

function eliminarProducto(button) {
  button.closest('.producto-carrito').remove();
  actualizarTotales();

  const productosCarrito = document.querySelectorAll('.producto-carrito');
  if (productosCarrito.length === 0) {
    document.getElementById('carritoConProductos').style.display = 'none';
    document.getElementById('carritoVacio').style.display = 'block';
  }
}

function vaciarCarrito() {
  if (confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
    document.getElementById('carritoConProductos').style.display = 'none';
    document.getElementById('carritoVacio').style.display = 'block';
  }
}

function actualizarTotales() {
  console.log('Totales actualizados');
}
