<?php defined('isCMS') or die; ?>

<!-- Modal -->
<div id="setpay" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="item modal-content">
			
			<div class="item_title">
				<div class="item_name">
					<?= $lang -> orders -> pay -> title; ?>
				</div>
				<div class="item_manage">
					<a href="#" data-dismiss="modal">
						<i class="fas fa-times" aria-hidden="true"></i>
					</a>
				</div>
			</div>
			
			<div class="item_body">
				<div class="item_row">
					<?= $lang -> orders -> pay -> description; ?>
				</div>
				<div class="item_row">
					
					<?php
						$mrh_login = "storyline";
						$mrh_pass1 = "KesV4mG3TfHYz73ymv7P";
						$inv_id = rand(100, 999);
						$inv_desc = "Товары для животных";
						$out_summ = "10.00";
						$IsTest = 1;
						$crc = hash("sha256", "$mrh_login:$out_summ:$inv_id:$mrh_pass1");
						print "<script language=JavaScript ".
							"src='https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?".
							"MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id".
							"&Description=$inv_desc&SignatureValue=$crc&IsTest=$IsTest'></script>";
					?>
					
					<form method="post" action="https://merchant.roboxchange.com/Index.aspx">
						<input type=hidden name=MrchLogin value=<?= $mrh_login; ?>>
						<input type=text name=OutSum value=<?= $out_summ; ?>>
						<input type=hidden name=InvId value=<?= $inv_id; ?>>
						<input type=hidden name=Desc value="<?= $inv_desc; ?>">
						<input type=hidden name=SignatureValue value=<?= $crc; ?>>
						<input type=hidden name=IsTest value="<?= $IsTest; ?>">
						<input type=submit value="Pay">
					</form>
					
					<form id=pay name=pay method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" accept-charset="windows-1251" >
						<p>пример платежа через сервис Web Merchant Interface</p>
						<p>заплатить 1 WMZ...</p>
						<p>
						<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="1.0" />
						<input type="hidden" name="LMI_PAYMENT_DESC" value="тестовый платеж" />
						<input type="hidden" name="LMI_PAYMENT_NO" value="1">
						<input type="hidden" name="LMI_PAYEE_PURSE" value="Z145179295679" />
						<input type="hidden" name="LMI_SIM_MODE" value="0" />
						
						
						
						
						<div class="item_row">
							<div class="item_row_label">
								<?= $lang -> modals -> project -> input_title; ?>
							</div>
							<div class="item_row_value">
								<input type="text" name="project[title]">
							</div>
						</div>
						
						<div class="item_row">
							<div class="item_row_label">
								<?= $lang -> modals -> project -> input_description; ?>
							</div>
							<div class="item_row_value">
								<textarea name="project[description]"></textarea>
							</div>
						</div>
						
						<div class="item_row separate">
						</div>
						
						<div class="item_row">
							<button type="submit" value="submit" class="button">
								<?= $lang -> action -> ok; ?>
							</button>
							<button class="button" data-dismiss="modal">
								<?= $lang -> action -> cancel; ?>
							</button>
						</div>
						
					</form>
					
				</div>
			</div>
			
		</div>

	</div>
</div>

<script>
$(function(){
/** start script **/

$('.set_pay').click(function(){
	$('#setpay').modal();
});

/** end script **/
});
</script>