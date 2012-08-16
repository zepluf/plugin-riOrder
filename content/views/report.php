<table>

	
<?php 
$year = 0;
while(!$reports->EOF):
    if($year != $reports->fields['row_year']):?>
    	<tr class="dataTableHeadingRow">
		<th><?php rie('Month')?></th>
		<th><?php rie('Year')?></th>
		<th><?php rie('Face Value')?></th>
		<th><?php rie('Cost of goods sold')?></th>
		<th><?php rie('Gross Sales')?></th>
		</tr>
    <?php endif;
    $year = $reports->fields['row_year'];

?>
	<tr class="dataTableRow">
		<td>
			<?php echo $reports->fields['row_month']?>
		</td>
		<td>
			<?php echo $reports->fields['row_year']?>
		</td>
		<td>
			<?php echo $reports->fields['face_total']?>
		</td>
		<td>
			<?php echo $reports->fields['cost']?>
		</td>
		<td>
			<?php echo $reports->fields['gross_sales']?>
		</td>
	</tr>


<?php 
    $reports->MoveNext();
endwhile;
?>
</table>