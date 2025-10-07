// cart sementara (disimpan di memori browser)
const cart = {};

function money(n){
  return n.toFixed(2);
}

function addToCart(id, name, price, img){
  id = String(id);
  if(cart[id]){
    cart[id].qty += 1;
  } else {
    cart[id] = { id, name, price: parseFloat(price), img, qty: 1 };
  }

  // tampilkan kontrol qty
  const qtyWrap = document.getElementById(`qty-${id}`);
  if(qtyWrap) qtyWrap.style.display = 'flex';

  updateQtyNumber(id);
  renderCart();
}

function increase(id){
  id = String(id);
  if(cart[id]){
    cart[id].qty += 1;
    updateQtyNumber(id);
    renderCart();
  }
}

function decrease(id){
  id = String(id);
  if(!cart[id]) return;
  cart[id].qty -= 1;
  if(cart[id].qty <= 0){
    delete cart[id];
    const qtyWrap = document.getElementById(`qty-${id}`);
    if(qtyWrap) qtyWrap.style.display = 'none';
  } else {
    updateQtyNumber(id);
  }
  renderCart();
}

function updateQtyNumber(id){
  const el = document.getElementById(`qty-number-${id}`);
  if(el && cart[id]) el.innerText = cart[id].qty;

  // update jumlah total item di ikon cart
  document.getElementById('cart-count').innerText =
    Object.values(cart).reduce((s, it) => s + it.qty, 0);
}

function renderCart(){
  const ul = document.getElementById('cart-items');
  ul.innerHTML = '';
  let total = 0;

  for(const key in cart){
    const it = cart[key];
    const li = document.createElement('li');
    li.innerHTML = `
      <div class="ci-left">
        <img src="/images/${it.img}" alt="${it.name}">
        <div class="ci-info">
          <div class="name">${it.name}</div>
          <div class="muted">${it.qty} x $${money(it.price)}</div>
        </div>
      </div>
      <div class="ci-right">
        $${money(it.qty * it.price)}
        <div style="font-size:12px;margin-top:6px;">
          <button onclick="decrease(${it.id})">−</button>
          <button onclick="increase(${it.id})">+</button>
        </div>
      </div>
    `;
    ul.appendChild(li);
    total += it.qty * it.price;
  }

  document.getElementById('total').innerText = money(total);
}

function confirmOrder(){
  if(Object.keys(cart).length === 0){
    alert('Keranjang kosong. Tambahkan item dulu.');
    return;
  }
  let summary = 'Order summary:\n';
  let total = 0;
  for(const k in cart){
    summary += `${cart[k].name} — ${cart[k].qty} x $${money(cart[k].price)} = $${money(cart[k].qty * cart[k].price)}\n`;
    total += cart[k].qty * cart[k].price;
  }
  summary += `\nTOTAL: $${money(total)}\n\nConfirm order?`;

  if(confirm(summary)){
    alert('Order confirmed (demo).');
    for(const k in cart) delete cart[k];
    document.querySelectorAll('.qty-controls').forEach(n=>n.style.display='none');
    renderCart();
  }
}

// render awal
renderCart();
