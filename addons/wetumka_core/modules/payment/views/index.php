<h2 id="page_title"><?php echo $message; ?></h2>
<h1>Braintree Credit Card Transaction Form</h1>
<div>
	<form action="/payment" method="POST" id="braintree-payment-form">
		<p>
            <label>Amount</label>
            <input type="text" size="20" autocomplete="off" name="value" />
        </p>

		<p>
			<label>Card Number</label>
			<input type="text" size="20" autocomplete="off" value="4111111111111111" data-encrypted-name="number" />
		</p>
		<p>
			<label>CVV</label>
			<input type="text" size="4" autocomplete="off" value="111" data-encrypted-name="cvv" />
		</p>
		<p>
			<label>Expiration (MM/YYYY)</label>
			<input type="text" size="2" name="month" value="11" />
			/
			<input type="text" size="4" name="year" value="2015" />
		</p>
		<input type="submit" id="submit" />
	</form>
</div>

<script type="text/javascript" src="https://js.braintreegateway.com/v1/braintree.js"></script>
<script type="text/javascript">
	var braintree = Braintree.create("MIIBCgKCAQEAsivt43hmDKXhvq1weKZAJF9xznSrcfuLWnGqBay/os8PVV94nByAFIwaUdM6dtPBasqT3FTQ7P27f9HVLoL5dk7CzU3k6psuImbft/e13Tm+gPdaXfMWh8g9dStE8uKeRiD1SVIigv94ehJ7tkpnJKuQyiAz4hf5Dscot46GfEEZ+jWdDInY0/cpqRwUakQwZBdFg6XpDXFzMQ03bAngMjeUmHj34JIiZEMG8Jcs1itoZe/zble43K6ZVBhQoZoFXdQq/CZlWYaZuwGPhS+MFPFTElA0wgDdnQrn6NboCCHY1MjEiylzsDSeAIJlEcAnYXRK1mgoDD4cJaoyJs7c8wIDAQAB");
	braintree.onSubmitEncryptForm('braintree-payment-form');
</script>