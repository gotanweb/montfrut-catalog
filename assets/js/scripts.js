document.addEventListener('DOMContentLoaded', function () {
  const filterButtons = document.querySelectorAll('.filter-btn');
  const checkboxes = document.querySelectorAll('.filter-option');
  const productCards = document.querySelectorAll('.product-card');
  const cleanFiltersBtn = document.getElementById('cleanFilters');
  const floatingCart = document.getElementById('floating-cart');
  const cartCountElement = document.getElementById('cart-count');
  const cartTotalElement = document.getElementById('cart-total');

  let activeCategory = 'all';
  let cart = {};
  let currentProduct = null;
  let currentProductOptions = {};

  // Cargar carrito guardado
  const savedCart = JSON.parse(localStorage.getItem('montfrut_cart') || '{}');
  cart = { ...savedCart };
  updateFloatingCart();

  /* ---------------- FILTRADO ---------------- */
  function applyFilters() {
    const selectedTags = Array.from(checkboxes)
      .filter(cb => cb.checked)
      .map(cb => cb.value);

    productCards.forEach(card => {
      const cardCategory = card.getAttribute('data-category');
      const cardTags = card.getAttribute('data-tags').split(' ');

      const matchCategory = activeCategory === 'all' || cardCategory === activeCategory;
      const matchTags = selectedTags.length === 0 || selectedTags.every(tag => cardTags.includes(tag));

      card.style.display = matchCategory && matchTags ? 'block' : 'none';
    });
  }

  // Eventos de filtrado por categoría
  filterButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      filterButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeCategory = btn.getAttribute('data-category');
      applyFilters();
    });
  });

  // Eventos de filtrado por checkboxes
  checkboxes.forEach(cb => {
    cb.addEventListener('change', applyFilters);
  });

  // Limpiar filtros
  if (cleanFiltersBtn) {
    cleanFiltersBtn.addEventListener('click', function () {
      checkboxes.forEach(checkbox => checkbox.checked = false);
      filterButtons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-category') === 'all') {
          btn.classList.add('active');
        }
      });
      activeCategory = 'all';
      applyFilters();
    });
  }

  // Inicializar filtros
  applyFilters();

  /* ---------------- MODAL DE PRODUCTO ---------------- */
  function openProductModal(productId) {
    const product = window.productsData.find(p => p.id === productId);
    if (!product) return;

    currentProduct = product;
    currentProductOptions = {};

    // Actualizar información del producto
    document.getElementById('modal-product-image').src = product.image;
    document.getElementById('modal-product-image').alt = product.title;
    document.getElementById('modal-product-title').textContent = product.title;
    document.getElementById('modal-product-description').textContent = product.description;
    document.getElementById('product-quantity').textContent = '1';

    // Limpiar opciones anteriores
    document.getElementById('product-options').innerHTML = '';

    // Crear opciones de personalización si existen
    if (product.options && product.options.length > 0) {
      product.options.forEach((option, optionIndex) => {
        const optionDiv = document.createElement('div');
        optionDiv.className = 'product-option';
        
        let optionHtml = `<h6>${option.name}${option.required ? ' *' : ''}</h6>`;
        
        option.choices.forEach((choice, choiceIndex) => {
          const priceText = choice.price > 0 ? ` (+$${choice.price.toFixed(2)})` : '';
          optionHtml += `
            <div class="option-choice" data-option="${optionIndex}" data-choice="${choiceIndex}">
              <span>${choice.label}</span>
              <span class="option-price">${priceText}</span>
            </div>
          `;
        });
        
        optionDiv.innerHTML = optionHtml;
        document.getElementById('product-options').appendChild(optionDiv);

        // Seleccionar primera opción por defecto si es requerida
        if (option.required) {
          currentProductOptions[optionIndex] = 0;
          optionDiv.querySelector('.option-choice').classList.add('selected');
        }
      });

      // Eventos para opciones
      document.querySelectorAll('.option-choice').forEach(choice => {
        choice.addEventListener('click', function() {
          const optionIndex = parseInt(this.dataset.option);
          const choiceIndex = parseInt(this.dataset.choice);
          
          // Remover selección anterior en la misma opción
          document.querySelectorAll(`[data-option="${optionIndex}"]`).forEach(c => c.classList.remove('selected'));
          
          // Seleccionar nueva opción
          this.classList.add('selected');
          currentProductOptions[optionIndex] = choiceIndex;
          
          updateModalPrice();
        });
      });
    }

    updateModalPrice();

    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
  }

  function updateModalPrice() {
    if (!currentProduct) return;
    
    let totalPrice = currentProduct.price;
    
    // Sumar precios de opciones seleccionadas
    if (currentProduct.options) {
      currentProduct.options.forEach((option, optionIndex) => {
        if (currentProductOptions[optionIndex] !== undefined) {
          const selectedChoice = option.choices[currentProductOptions[optionIndex]];
          totalPrice += selectedChoice.price;
        }
      });
    }
    
    // Multiplicar por cantidad
    const quantity = parseInt(document.getElementById('product-quantity').textContent);
    const finalPrice = totalPrice * quantity;
    
    document.getElementById('modal-product-price').textContent = `$${finalPrice.toFixed(2)}`;
  }

  // Eventos del modal de producto
  document.getElementById('decrease-qty').addEventListener('click', () => {
    const qtyElement = document.getElementById('product-quantity');
    const currentQty = parseInt(qtyElement.textContent);
    if (currentQty > 1) {
      qtyElement.textContent = currentQty - 1;
      updateModalPrice();
    }
  });

  document.getElementById('increase-qty').addEventListener('click', () => {
    const qtyElement = document.getElementById('product-quantity');
    const currentQty = parseInt(qtyElement.textContent);
    qtyElement.textContent = currentQty + 1;
    updateModalPrice();
  });

  document.getElementById('add-to-cart-modal').addEventListener('click', () => {
    if (!currentProduct) return;

    // Validar opciones requeridas
    if (currentProduct.options) {
      for (let i = 0; i < currentProduct.options.length; i++) {
        const option = currentProduct.options[i];
        if (option.required && currentProductOptions[i] === undefined) {
          alert(`Please select ${option.name}`);
          return;
        }
      }
    }

    // Calcular precio base
    let basePrice = currentProduct.price;
    let optionsText = '';
    
    if (currentProduct.options) {
      currentProduct.options.forEach((option, optionIndex) => {
        if (currentProductOptions[optionIndex] !== undefined) {
          const selectedChoice = option.choices[currentProductOptions[optionIndex]];
          basePrice += selectedChoice.price;
          if (optionsText) optionsText += ', ';
          optionsText += `${option.name}: ${selectedChoice.label}`;
        }
      });
    }

    const quantity = parseInt(document.getElementById('product-quantity').textContent);
    
    // Crear clave única para el producto con opciones
    const cartKey = `${currentProduct.id}_${JSON.stringify(currentProductOptions)}`;
    
    // Agregar al carrito
    if (cart[cartKey]) {
      cart[cartKey].quantity += quantity;
    } else {
      cart[cartKey] = {
        id: currentProduct.id,
        title: currentProduct.title,
        image: currentProduct.image,
        price: basePrice,
        quantity: quantity,
        options: currentProductOptions,
        optionsText: optionsText
      };
    }

    localStorage.setItem('montfrut_cart', JSON.stringify(cart));
    updateFloatingCart();
    
    // Cerrar modal
    bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
  });

  // Eventos para abrir modal de producto
  document.querySelectorAll('.btn-buy').forEach(button => {
    button.addEventListener('click', function () {
      const productId = this.dataset.productId;
      openProductModal(productId);
    });
  });

  /* ---------------- CARRITO FLOTANTE ---------------- */
  function updateFloatingCart() {
    const itemCount = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);

    if (itemCount > 0) {
      floatingCart.style.display = 'block';
      cartCountElement.textContent = itemCount;
      cartTotalElement.textContent = `$${totalPrice.toFixed(2)}`;
    } else {
      floatingCart.style.display = 'none';
    }
  }

  /* ---------------- MODAL DEL CARRITO ---------------- */
  function updateCartModal() {
    const cartItemsContainer = document.getElementById('cart-items');
    const modalCartTotal = document.getElementById('modal-cart-total');
    
    if (Object.keys(cart).length === 0) {
      cartItemsContainer.innerHTML = '<div class="alert alert-info">Your cart is empty</div>';
      modalCartTotal.textContent = '$0.00';
      return;
    }

    let html = '';
    let total = 0;

    Object.entries(cart).forEach(([key, item]) => {
      const subtotal = item.price * item.quantity;
      total += subtotal;

      html += `
        <div class="cart-item">
          <div class="row align-items-center">
            <div class="col-2">
              <img src="${item.image}" alt="${item.title}" class="cart-item-image">
            </div>
            <div class="col-7">
              <div class="cart-item-title">${item.title}</div>
              ${item.optionsText ? `<div class="cart-item-options">${item.optionsText}</div>` : ''}
              <div class="d-flex align-items-center mt-2">
                <button class="btn btn-sm btn-outline-secondary me-2 cart-decrease" data-key="${key}">-</button>
                <span class="me-2">${item.quantity}</span>
                <button class="btn btn-sm btn-outline-secondary cart-increase" data-key="${key}">+</button>
              </div>
            </div>
            <div class="col-2">
              <div class="cart-item-price">$${subtotal.toFixed(2)}</div>
            </div>
            <div class="col-1">
              <button class="cart-item-remove" data-key="${key}">×</button>
            </div>
          </div>
        </div>
      `;
    });

    cartItemsContainer.innerHTML = html;
    modalCartTotal.textContent = `$${total.toFixed(2)}`;

    // Agregar event listeners a los botones recién creados
    addCartEventListeners();
  }

  // Función separada para agregar event listeners del carrito
  function addCartEventListeners() {
    // Botones de disminuir cantidad
    document.querySelectorAll('.cart-decrease').forEach(btn => {
      btn.addEventListener('click', function() {
        const key = this.dataset.key;
        if (cart[key]) {
          cart[key].quantity -= 1;
          if (cart[key].quantity <= 0) {
            delete cart[key];
          }
          localStorage.setItem('montfrut_cart', JSON.stringify(cart));
          updateFloatingCart();
          updateCartModal();
          validateTerms();
        }
      });
    });

    // Botones de aumentar cantidad
    document.querySelectorAll('.cart-increase').forEach(btn => {
      btn.addEventListener('click', function() {
        const key = this.dataset.key;
        if (cart[key]) {
          cart[key].quantity += 1;
          localStorage.setItem('montfrut_cart', JSON.stringify(cart));
          updateFloatingCart();
          updateCartModal();
          validateTerms();
        }
      });
    });

    // Botones de eliminar producto
    document.querySelectorAll('.cart-item-remove').forEach(btn => {
      btn.addEventListener('click', function() {
        const key = this.dataset.key;
        delete cart[key];
        localStorage.setItem('montfrut_cart', JSON.stringify(cart));
        updateFloatingCart();
        updateCartModal();
        validateTerms();
      });
    });
  }

  // Función para vaciar el carrito completamente
  function emptyCart() {
    if (confirm('Are you sure you want to empty your cart?')) {
      cart = {};
      localStorage.removeItem('montfrut_cart');
      updateFloatingCart();
      updateCartModal();
      validateTerms();
    }
  }

  // Event listener para el botón Empty Cart
  document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'empty-cart-btn') {
      emptyCart();
    }
  });

  // Abrir modal del carrito
  document.getElementById('openCartModal').addEventListener('click', () => {
    updateCartModal();
    const modal = new bootstrap.Modal(document.getElementById('cartModal'));
    modal.show();
  });

  /* ---------------- TÉRMINOS Y CONDICIONES ---------------- */
  function validateTerms() {
    const priceTerms = document.getElementById('price-terms').checked;
    const allergyTerms = document.getElementById('allergy-terms').checked;
    const hasProducts = Object.keys(cart).length > 0;
    const sendBtn = document.getElementById('send-whatsapp');
    const warning = document.getElementById('terms-warning');

    const isValid = priceTerms && allergyTerms && hasProducts;
    sendBtn.disabled = !isValid;

    if (hasProducts && (!priceTerms || !allergyTerms)) {
      warning.style.display = 'block';
    } else {
      warning.style.display = 'none';
    }
  }

  document.getElementById('price-terms').addEventListener('change', validateTerms);
  document.getElementById('allergy-terms').addEventListener('change', validateTerms);

  /* ---------------- ENVÍO A WHATSAPP ---------------- */
  document.getElementById('send-whatsapp').addEventListener('click', () => {
    const name = document.getElementById('customer-name').value.trim();
    const phone = document.getElementById('customer-phone').value.trim();
    const company = document.getElementById('customer-company').value.trim();
    const celebration = document.getElementById('customer-celebration').value;
    const comments = document.getElementById('customer-comments').value.trim();

    if (!name || !phone) {
      alert('Please complete all required fields.');
      return;
    }

    // Crear mensaje
    let message = `Hello, I'm ${name}`;
    if (company) message += ` from ${company}`;
    message += `.%0APhone: ${phone}%0A`;
    if (celebration !== 'No') message += `Celebration: ${celebration}%0A`;
    message += `%0AI would like to place the following order:%0A%0A`;

    let total = 0;
    Object.values(cart).forEach(item => {
      const subtotal = item.price * item.quantity;
      total += subtotal;
      
      message += `${item.title}`;
      if (item.optionsText) message += ` (${item.optionsText})`;
      message += ` - Qty: ${item.quantity} - $${subtotal.toFixed(2)}%0A`;
    });

    message += `%0A*TOTAL: $${total.toFixed(2)}*`;
    
    if (comments) {
      message += `%0A%0AComments: ${comments}`;
    }

    // Abrir WhatsApp
    const whatsappUrl = `https://api.whatsapp.com/send?phone=${window.whatsappNumber}&text=${message}`;
    window.open(whatsappUrl, '_blank');

    // Cerrar modal del carrito
    bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();

    // Mostrar modal de éxito
    setTimeout(() => {
      const successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    }, 500);
  });

  // Cerrar modal de éxito y limpiar carrito
  document.getElementById('close-success-modal').addEventListener('click', () => {
    cart = {};
    localStorage.removeItem('montfrut_cart');
    updateFloatingCart();
    bootstrap.Modal.getInstance(document.getElementById('successModal')).hide();
    
    // Limpiar formulario
    document.getElementById('customer-name').value = '';
    document.getElementById('customer-phone').value = '';
    document.getElementById('customer-company').value = '';
    document.getElementById('customer-celebration').value = 'No';
    document.getElementById('customer-comments').value = '';
    document.getElementById('price-terms').checked = false;
    document.getElementById('allergy-terms').checked = false;
  });

  // Inicializar validación de términos
  validateTerms();
});