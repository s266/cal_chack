<?php 
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'test';

$conn = mysqli_connect($host, $user, $pass, $dbname);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bootstrap Modal Example</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <div class="row">
    <?php
    $i = 1;
    $fetch = mysqli_query($conn, "SELECT * FROM status_maintenance");
    while($data = mysqli_fetch_array($fetch)) {
        $amount_total = $data['amount'];
    ?>
    <div class="col-md-4 mb-4">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0"><?php echo $data['name']; ?></h5>
        </div>
        <div class="card-body">
          <h6 class="card-subtitle mb-2 text-success">INR <?php echo $amount_total; ?></h6>
          <p class="card-text"><?php echo $data['description']; ?></p>
          <button class="btn btn-primary"
                  data-bs-toggle="modal"
                  onclick="openModal(this)"
                  data-bs-target="#myModal<?php echo $data['id'];?>"
                  data-amount="<?php echo $amount_total; ?>">
            View Package Details
          </button>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal<?php echo $data['id'];?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $data['id'];?>" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <form method="post" action="product_billing.php" onsubmit="return validateAmount(this)">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalLabel<?php echo $data['id'];?>"><b>Package Amount: INR <?php echo $amount_total; ?></b></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <input type="hidden" name="package_id" value="<?php echo $data['id'];?>">
            <input type="hidden" name="amount" value="<?php echo $amount_total;?>" class="pack_amounts">

            <div class="modal-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Select</th>
                    <th>#</th>
                    <th>Product</th>
                    <th>Price</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $products = [
                      ["Punch Tulsi", 399],
                      ["Skin Care Lotion", 300],
                      ["Pain Oil", 400],
                      ["Ortho Cap", 900],
                      ["Bio Plus Hair Oil", 500],
                      ["Hair Fall Kit", 1800],
                      ["Stem Cell", 1500],
                      ["Stamina Kit", 1800],
                      ["Breast Cream", 650],
                      ["Slimsite Cap", 1250],
                      ["Eye Drop", 200]
                  ];
                  foreach ($products as $index => $product) {
                   
                     $value = htmlspecialchars(json_encode(["name" => $product[0], "price" => $product[1]]));
                     echo "<tr>
                           <td><input type='checkbox' name='product[]' value='{$value}' onchange='updateTotal(this.closest(\".modal\"))'></td>
                           <td>" . ($index + 1) . "</td>
                           <td>{$product[0]}</td>
                           <td>{$product[1]}/-</td>
                           </tr>";
               }
                     ?>

                </tbody>
              </table>

              <div id="error_message" class="text-danger d-none mb-2">
                You can only buy products worth INR <span id="max_limit_show"></span> or less.
              </div>
              <div id="total_message" class="text-success fw-bold">Total: INR 0/-</div>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Proceed To Checkout</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php $i++; } ?>
  </div>
</div>

<!-- Scripts -->
<script>
// Open modal and set maxAmount
function openModal(button) {
    const modalId = button.getAttribute('data-bs-target');
    const modal = document.querySelector(modalId);
    window.maxAmount = parseFloat(button.getAttribute('data-amount'));
    modal.querySelector('#max_limit_show').innerText = window.maxAmount;
    updateTotal(modal);
}

// Update total inside a specific modal
function updateTotal(modal) {
    const checkboxes = modal.querySelectorAll('input[name="product[]"]');
    let totalAmount = 0;

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            let productData = JSON.parse(checkbox.value);
            totalAmount += parseInt(productData.price);
        }
    });

    modal.querySelector('#total_message').innerHTML = "Total: INR " + totalAmount + "/-";

    if (totalAmount > window.maxAmount) {
        modal.querySelector('#error_message').classList.remove('d-none');
        modal.querySelector('button[type="submit"]').disabled = true;
    } else {
        modal.querySelector('#error_message').classList.add('d-none');
        modal.querySelector('button[type="submit"]').disabled = false;
    }
}


// Validate before form submit
function validateAmount(form) {
    const modal = form.closest('.modal');
    const checkboxes = modal.querySelectorAll('input[name="product[]"]');
    let totalAmount = 0;

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            let productData = JSON.parse(checkbox.value);
            totalAmount += parseInt(productData.price);
        }
    });

    if (totalAmount > window.maxAmount) {
        alert("You can only buy products worth INR " + window.maxAmount + " or less.");
        return false;
    }
    return true;
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
