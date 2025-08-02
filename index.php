<?php include 'config.php'; ?>
<?php include __DIR__ . '/includes/product_data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Montfrut Catalog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="assets/css/styles.css" rel="stylesheet">
</head>
<body>

<!-- HERO SECTION / PORTADA -->
<div class="hero-section">
  <div class="hero-image">
    <img src="assets/img/hero-banner.jpg" alt="Montfrut Hero" class="w-100">
    <div class="hero-overlay">
      <div class="container text-center">
        <img src="assets/img/logo-montfrut.png" alt="Montfrut Logo" class="hero-logo mb-3">
        <h2 class="hero-title text-white mb-3">Premium Artisan Chocolates</h2>
        <p class="hero-description text-white mb-4">Discover our exquisite collection of handcrafted chocolates, made with the finest ingredients and traditional techniques.</p>
        
        <!-- SOCIAL MEDIA ICONS -->
        <div class="social-icons">
          <a href="#" class="social-icon" target="_blank"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-icon" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-icon" target="_blank"><i class="fab fa-tiktok"></i></a>
          <a href="#" class="social-icon" target="_blank"><i class="fas fa-globe"></i></a>
          <a href="#" class="social-icon" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid py-4">
  <div class="text-center mb-4">
    <h1 class="fw-bold">Our Catalog</h1>
    <div class="category-buttons">
      <button class="btn btn-sm filter-btn active" data-category="all">All</button>
      <button class="btn btn-sm filter-btn" data-category="bombons">Bombons</button>
      <button class="btn btn-sm filter-btn" data-category="bars">Bars</button>
      <button class="btn btn-sm filter-btn" data-category="mendiants">Mendiants</button>
      <button class="btn btn-sm filter-btn" data-category="seasonal">Seasonal</button>
      <button class="btn btn-sm filter-btn" data-category="christmas">Christmas</button>
    </div>
  </div>

  <div class="row">
    <!-- FILTROS -->
    <div class="col-md-3 mb-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Filter by:</h5>
        <div class="d-flex gap-2">
          <button id="cleanFilters" class="btn btn-filter btn-sm">Clean filters</button>
        </div>
      </div>

      <div class="filter-group mb-3">
        <strong>Chocolate</strong><br>
        <div><input type="checkbox" class="form-check-input filter-option" value="dark"> <label>Dark Chocolate</label></div>
        <div><input type="checkbox" class="form-check-input filter-option" value="milk"> <label>Milk Chocolate</label></div>
        <div><input type="checkbox" class="form-check-input filter-option" value="white"> <label>White Chocolate</label></div>
        <div><input type="checkbox" class="form-check-input filter-option" value="oreo"> <label>Oreo</label></div>
      </div>

      <div class="filter-group mb-3">
        <strong>Fruits</strong><br>
        <div><input type="checkbox" class="form-check-input filter-option" value="strawberries"> <label>Dried Strawberries</label></div>
        <div><input type="checkbox" class="form-check-input filter-option" value="blueberries"> <label>Dried Blueberries</label></div>
        <div><input type="checkbox" class="form-check-input filter-option" value="oranges"> <label>Dried Oranges</label></div>
      </div>

      <div class="filter-group">
        <strong>Nuts</strong><br>
        <div><input type="checkbox" class="form-check-input filter-option" value="peanuts"> <label>Peanuts</label></div>
        <div><input type="checkbox" class="form-check-input filter-option" value="pistachios"> <label>Pistachios</label></div>
        <div><input type="checkbox" class="form-check-input filter-option" value="cashews"> <label>Cashews</label></div>
        <div><input type="checkbox" class="form-check-input filter-option" value="walnuts"> <label>Walnuts</label></div>
      </div>

      <!-- BOTÓN ALLERGIES -->
      <div class="mt-4">
        <button class="btn allergies-btn" data-bs-toggle="modal" data-bs-target="#allergiesModal">
          ⚠️ Allergies
        </button>
      </div>
    </div>

    <!-- CATÁLOGO -->
    <div class="col-md-9">
      <div class="row" id="product-grid">
        <?php foreach ($products as $product): ?>
          <div class="col-lg-4 col-md-6 col-sm-6 mb-4 product-card" 
               data-category="<?= $product['category'] ?>" 
               data-tags="<?= implode(' ', $product['tags']) ?>"
               data-product-id="<?= $product['id'] ?>"
               data-price="<?= $product['price'] ?>">
            <img src="<?= $product['image'] ?>" class="product-img" alt="<?= $product['title'] ?>">
            <h5><?= $product['title'] ?></h5>
            <p class="fw-bold text-center mb-2">$<?= number_format($product['price'], 2) ?></p>
            <div class="d-flex justify-content-center gap-2 mt-2">
              <button class="btn btn-buy btn-sm" data-product-id="<?= $product['id'] ?>">Add to cart</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- BOTÓN FLOTANTE DEL CARRITO -->
<div id="floating-cart" class="floating-cart" style="display: none;">
  <button class="btn floating-cart-btn" id="openCartModal">
    <span id="cart-text">Your order</span>
    <span id="cart-count" class="cart-count">0</span>
    <span id="cart-total" class="cart-total">$0.00</span>
  </button>
</div>

<!-- MODAL DE PRODUCTO -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideInLeft">
    <div class="modal-content product-modal-content">
      <div class="modal-header border-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <img id="modal-product-image" src="" alt="" class="modal-product-img">
        </div>
        <h4 id="modal-product-title" class="text-center mb-2"></h4>
        <p id="modal-product-description" class="text-center mb-3"></p>
        
        <!-- OPCIONES DE PERSONALIZACIÓN -->
        <div id="product-options" class="mb-3"></div>
        
        <div class="text-center mb-3">
          <h5 id="modal-product-price" class="text-primary">$0.00</h5>
        </div>
        
        <!-- SELECTOR DE CANTIDAD -->
        <div class="quantity-selector d-flex justify-content-center align-items-center mb-4">
          <button class="btn btn-outline-secondary btn-sm" id="decrease-qty">-</button>
          <span id="product-quantity" class="mx-3 fs-5">1</span>
          <button class="btn btn-outline-secondary btn-sm" id="increase-qty">+</button>
        </div>
        
        <div class="text-center">
          <button class="btn btn-buy w-100" id="add-to-cart-modal">Add to cart</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DEL CARRITO -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideInLeft modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Your Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="cart-items"></div>
        <div class="cart-total-section p-3 mt-3">
          <h4 class="text-center mb-0">Total: <span id="modal-cart-total">$0.00</span></h4>
        </div>
        
        <!-- FORMULARIO DE DATOS -->
        <div class="customer-form mt-4">
          <h6 class="mb-3">Customer Information</h6>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="customer-name" class="form-label">Name *</label>
              <input type="text" id="customer-name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="customer-phone" class="form-label">Phone *</label>
              <input type="text" id="customer-phone" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="customer-company" class="form-label">Company (optional)</label>
              <input type="text" id="customer-company" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label for="customer-celebration" class="form-label">Celebration</label>
              <select id="customer-celebration" class="form-select">
                <option value="No">No</option>
                <option value="Birthday">Birthday</option>
                <option value="Anniversary">Anniversary</option>
                <option value="Graduation">Graduation</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label for="customer-comments" class="form-label">Comments</label>
            <textarea id="customer-comments" class="form-control" rows="3"></textarea>
          </div>
          
          <!-- TÉRMINOS Y CONDICIONES -->
          <div class="terms-section p-3 mb-3">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="price-terms" required>
              <label class="form-check-label" for="price-terms">
                <strong>I understand that this is not a final purchase confirmation and that the displayed prices do NOT include taxes or shipping costs.</strong>
              </label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="allergy-terms" required>
              <label class="form-check-label" for="allergy-terms">
                <strong>I confirm that I have read the product allergy descriptions.</strong>
              </label>
            </div>
          </div>
          
          <div id="terms-warning" class="alert alert-warning" style="display: none;">
            <strong>⚠️ Attention:</strong> You must agree to all conditions before sending the message.
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-next" id="send-whatsapp" disabled>Continue</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DE ALERGIAS -->
<div class="modal fade" id="allergiesModal" tabindex="-1" aria-labelledby="allergiesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="allergiesModalLabel">⚠️ Allergy Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <strong>IMPORTANT:</strong> Please read this information carefully before placing your order.
        </div>
        
        <h6 class="fw-bold mb-3">Our products may contain or have been in contact with:</h6>
        
        <ul class="list-unstyled">
          <li class="mb-2">🥜 <strong>Nuts:</strong> Almonds, walnuts, pistachios, cashews, peanuts</li>
          <li class="mb-2">🥛 <strong>Dairy:</strong> Milk, butter, cream</li>
          <li class="mb-2">🌾 <strong>Gluten:</strong> Wheat, oats, barley</li>
          <li class="mb-2">🥚 <strong>Eggs:</strong> Egg whites and yolks</li>
          <li class="mb-2">🍓 <strong>Fruits:</strong> Strawberries, blueberries, oranges</li>
          <li class="mb-2">🍫 <strong>Cocoa:</strong> Chocolate and derivatives</li>
        </ul>
        
        <div class="alert alert-danger mt-3">
          <strong>⚠️ WARNING:</strong> If you have food allergies, consult with our team before placing your order. We are not responsible for allergic reactions due to lack of information from the customer.
        </div>
        
        <p class="text-muted mt-3">
          <small>For specific ingredient inquiries, contact us directly via WhatsApp.</small>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Understood</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DE CONFIRMACIÓN -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <h5 class="text-success mb-3">✅ Thank you for contacting us!</h5>
        <p>Your inquiry has been sent successfully via WhatsApp.</p>
        <button class="btn btn-primary" id="close-success-modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Pasar datos de productos a JavaScript
window.productsData = <?= json_encode($products) ?>;
window.whatsappNumber = "<?= $whatsappNumber ?? '19174740694' ?>";
</script>
<script src="assets/js/scripts.js"></script>
</body>
</html>