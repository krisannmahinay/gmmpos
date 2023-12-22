<tr>
	<td colspan='19'>
		<table width='50%'>
		<tr>
			<th class='extamount WithBorders' colspan=9 nowrap>NON-CASH PAYMENTS</th>
			<?php
				include_once 'rep.XREAD.0.php';
			?>
			</th>
		</tr>
		
		<tr>
			<td class='wpadd WithBorders' align='right'>CHEQUE PAYMENTS</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CR_CHQUE_COUNT = ZERO_check($_SESSION['sesnXR_CR_CHQUE_COUNT'], 0);
				echo number_format($sesnXR_CR_CHQUE_COUNT,0);
				?>
			</td>
			
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CR_CHQUE = ZERO_check($_SESSION['sesnXR_CR_CHQUE'], 2);
				echo $sesnXR_CR_CHQUE;
				?>
			</td>
		</tr>
		
		<tr>
			<td class='wpadd WithBorders' align='right'>CHARGE</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CHARGE_COUNT = ZERO_check($_SESSION['sesnXR_CHARGE_COUNT'], 0);
				echo $sesnXR_CHARGE_COUNT;
				?>
			</td>
			
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CHARGE = ZERO_check($_SESSION['sesnXR_CHARGE'], 2);
				echo $sesnXR_CHARGE;
				?>
			</td>
		</tr>
		
		<tr>
			<td class='wpadd WithBorders' align='right'>CREDIT CARD</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CR_CARD_COUNT = ZERO_check($_SESSION['sesnXR_CR_CARD_COUNT'], 0);
				echo $sesnXR_CR_CARD_COUNT;
				?>
			</td>
	
			<td class='wpadd WithBorders' align='right'>
				<?php 
				$sesnXR_CR_CARD = ZERO_check($_SESSION['sesnXR_CR_CARD'], 2);
				echo $sesnXR_CR_CARD;
				?>
			</td>
		</tr>

		<tr>
			<td class='wpadd WithBorders' align='right'>CREDIT MEMO</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CM_COUNT = ZERO_check($_SESSION['sesnXR_CM_COUNT'], 0);
				echo $sesnXR_CM_COUNT;
				?>
			</td>
			
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_CM = ZERO_check($_SESSION['sesnXR_CM'], 2);
				echo $sesnXR_CM;
				?>
			</td>
		</tr>
  
		<tr>
			<td class='wpadd WithBorders' align='right'>DEBIT CARD</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_DR_CARD_COUNT = ZERO_check($_SESSION['sesnXR_DR_CARD_COUNT'], 0);
				echo $sesnXR_DR_CARD_COUNT;
				?>
			</td>
			
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_DR_CARD = ZERO_check($_SESSION['sesnXR_DR_CARD'], 2);
				echo $sesnXR_DR_CARD;
				?>
			</td>
		</tr>

		<tr>
			<td class='wpadd WithBorders' align='right'>GIFT CERTI</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_GIFT_CERT_COUNT = ZERO_check($_SESSION['sesnXR_GIFT_CERT_COUNT'], 0);
				echo $sesnXR_GIFT_CERT_COUNT;
				?>
			</td>
			<td class='wpadd WithBorders' align='right'>
				<?php
				$sesnXR_GIFT_CERT = ZERO_check($_SESSION['sesnXR_GIFT_CERT'], 2);
				echo $sesnXR_GIFT_CERT;
				?>
			</td>
		</tr>
		
		<tr>
			<td class='extamount WithBorders' align='right'>Total NON-CASH</td>
			<td class='extamount WithBorders' align='right'>
				<?php
				$sesnXR_NonCash_COUNT = $_SESSION['sesnXR_NonCash_COUNT'];
				echo number_format($sesnXR_NonCash_COUNT,0);
				?>
			</td>
			<td class='extamount WithBorders' align='right'>
				<?php
				$sesnXR_NonCash_AMOUNT = $_SESSION['sesnXR_NonCash_AMOUNT'];
				echo number_format($sesnXR_NonCash_AMOUNT,2);
				?>
			</td>
		</tr>
		
		</table>
	</td>	
</tr>
