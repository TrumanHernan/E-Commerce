const metodoPagoRadios = document.querySelectorAll('input[name="metodo_pago"]');
const formularioTarjeta = document.getElementById('formulario-tarjeta');
const informacionEfectivo = document.getElementById('informacion-efectivo');
const informacionTransferencia = document.getElementById('informacion-transferencia');

metodoPagoRadios.forEach(radio => {
  radio.addEventListener('change', function() {
    formularioTarjeta.style.display = 'none';
    informacionEfectivo.style.display = 'none';
    informacionTransferencia.style.display = 'none';

    if (this.value === 'tarjeta_credito') {
      formularioTarjeta.style.display = 'block';
    } else if (this.value === 'efectivo') {
      informacionEfectivo.style.display = 'block';
    } else if (this.value === 'transferencia') {
      informacionTransferencia.style.display = 'block';
    }
  });
});

document.getElementById('numero_tarjeta').addEventListener('input', function() {
  let valor = this.value.replace(/\s/g, '');
  let formateado = valor.replace(/(\d{4})/g, '$1 ').trim();
  this.value = formateado;

  let displayNumero = valor.padEnd(16, '0').slice(0, 16);
  displayNumero = displayNumero.replace(/(\d{4})/g, '$1 ').trim();
  document.getElementById('display-numero').textContent = displayNumero || '0000 0000 0000 0000';
});

document.getElementById('titular').addEventListener('input', function() {
  let valor = this.value.toUpperCase();
  document.getElementById('display-titular').textContent = valor || 'NOMBRE COMPLETO';
});

document.getElementById('fecha_expiracion').addEventListener('input', function() {
  let valor = this.value.replace(/\D/g, '');
  if (valor.length >= 2) {
    valor = valor.slice(0, 2) + '/' + valor.slice(2, 4);
  }
  this.value = valor;
  document.getElementById('display-fecha').textContent = valor || 'MM/AA';
});

document.getElementById('cvv').addEventListener('input', function() {
  let valor = this.value.replace(/\D/g, '').slice(0, 3);
  let display = valor ? '*'.repeat(valor.length) : '***';
  document.getElementById('display-cvv').textContent = display;
});

document.getElementById('formularioCheckout').addEventListener('submit', function(e) {
  e.preventDefault();
  alert('¡Compra confirmada! Tu pedido será procesado en breve.');
});
