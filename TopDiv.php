<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
      <a href="Homepage.php" class="navbar-brand">SR Hardware Store</a>
        <form class="form-inline w-50" action="ProductList.php" method="get">
          <div class="input-group">
            <input type="text" class="form-control " placeholder="Search products" name="search">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
          </div>
        </form>
    
        <div class="navbar-nav">
          <a href="SignUp.php" class="nav-item nav-link">SignUp</a>
          <a href="ViewCart.php" class="nav-item nav-link"><img class="pb-2" width="40" height="40" src = '../IFU_Assets/shopping_cart.jpg' /></a>
        </div>
  </div>
</nav>