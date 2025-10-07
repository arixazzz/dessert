<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Desserts — Food Card</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <div class="page">
    <header class="header">
      <h1>Desserts</h1>
    </header>

    <main class="main">
      <section class="products-wrap">
        <div class="products-grid">
          @foreach($products as $p)
            <article class="card">
              <div class="img-wrap">
                <img src="/images/{{ $p['img'] }}" alt="{{ $p['title'] }}">
              </div>

              <div class="card-body">
                <small class="cat">{{ $p['category'] }}</small>
                <h3 class="title-card">{{ $p['title'] }}</h3>
                <div class="price">$<span>{{ number_format($p['price'], 2) }}</span></div>

                <!-- tombol Add -->
                <div class="actions">
                  <button class="btn-add"
  data-id="{{ $p['id'] }}"
  data-title="{{ $p['title'] }}"
  data-price="{{ $p['price'] }}"
  data-img="{{ $p['img'] }}">
  🛒 Add to Cart
</button>
                </div>

                <!-- qty control: akan disembunyikan saat qty 0 -->
                <div class="qty-controls" id="qty-{{ $p['id'] }}" style="display:none;">
                  <button class="qty-btn" onclick="decrease({{ $p['id'] }})">−</button>
                  <div class="qty-number" id="qty-number-{{ $p['id'] }}">1</div>
                  <button class="qty-btn" onclick="increase({{ $p['id'] }})">+</button>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </section>

      <aside class="cart-wrap">
        <div class="cart-card">
          <h2>Your Cart (<span id="cart-count">0</span>)</h2>
          <ul id="cart-items" class="cart-items"></ul>

          <div class="cart-total">
            <div>Order Total</div>
            <div class="total-amount">$<span id="total">0.00</span></div>
          </div>

          <div class="cart-actions">
            <label class="carbon">🌳 This is a <strong>carbon-neutral</strong> delivery</label>
            <button class="confirm-btn" onclick="confirmOrder()">Confirm Order</button>
          </div>
        </div>
      </aside>
    </main>
  </div>

  <!-- JavaScript: assets -->
  {{-- <script src="{{ asset('js/script.js') }}"></script> --}}
  <script>
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

document.querySelectorAll(".btn-add").forEach(btn => {
  btn.addEventListener("click", function () {
    const id = this.dataset.id;
    const title = this.dataset.title;
    const price = parseFloat(this.dataset.price);
    const img = this.dataset.img;

    addToCart(id, title, price, img);
  });
});

  </script>
</body>
</html>
