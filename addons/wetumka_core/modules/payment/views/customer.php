<h1>Braintree Customer Form</h1>
      <form action="/payment/customer" method="POST" id="braintree-payment-form">
      <h2>Customer Information</h2>
      <p>
        <label>First Name</label>
        <input type="text" name="first_name" id="first_name"></input>
      </p>
      <p>
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name"></input>
      </p>
      <p>
        <label for="postal_code">Postal Code</label>
        <input type="text" name="postal_code" id="postal_code"></input>
      </p>
      <h2>Credit Card</h2>
      <p>
        <label>Card Number</label>
        <input type="text" size="20" autocomplete="off" data-encrypted-name="number" />
      </p>
      <p>
        <label>CVV</label>
        <input type="text" size="4" autocomplete="off" data-encrypted-name="cvv" />
      </p>
      <p>
        <label>Expiration (MM/YYYY)</label>
        <input type="text" size="2" data-encrypted-name="month" /> / <input type="text" size="4" data-encrypted-name="year" />
      </p>
      <input class="submit-button" type="submit" />
    </form>
    <script type="text/javascript" src="https://js.braintreegateway.com/v1/braintree.js"></script>
    <script type="text/javascript">
      var braintree = Braintree.create("MIIBCgKCAQEAsivt43hmDKXhvq1weKZAJF9xznSrcfuLWnGqBay/os8PVV94nByAFIwaUdM6dtPBasqT3FTQ7P27f9HVLoL5dk7CzU3k6psuImbft/e13Tm+gPdaXfMWh8g9dStE8uKeRiD1SVIigv94ehJ7tkpnJKuQyiAz4hf5Dscot46GfEEZ+jWdDInY0/cpqRwUakQwZBdFg6XpDXFzMQ03bAngMjeUmHj34JIiZEMG8Jcs1itoZe/zble43K6ZVBhQoZoFXdQq/CZlWYaZuwGPhS+MFPFTElA0wgDdnQrn6NboCCHY1MjEiylzsDSeAIJlEcAnYXRK1mgoDD4cJaoyJs7c8wIDAQAB");
      braintree.onSubmitEncryptForm("braintree-payment-form");
    </script>