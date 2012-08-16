<?php $riview->get('loader')->load(array('jquery.lib', 'jquery.ui.lib', 'inline.js' => array('inline' => "$(document).ready(function() {	
		$('.datepicker').datepicker({ dateFormat: 'yy/mm/dd' })});")))?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
		<!-- body_text //-->
		<td width="100%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">

				<tr>
					<td width="100%">
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
								<td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
								</td>
							</tr>
						</table></td>
				</tr>
				<tr>
					<td>
						<!-- ri --> 
						<form action="<?php echo $router->generate('riorder_search')?>" method="GET">
                        <?php
			            			
            			// show reset search
            			if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
            			    echo '<a href="' . $router->generate('riorder_search') . '">' . zen_image_button('button_reset.gif', IMAGE_RESET) . '</a>&nbsp;&nbsp;';
            			}
            
            			echo HEADING_TITLE_SEARCH_DETAIL . ' ' . zen_draw_input_field('search') . zen_hide_session_id();
            			echo zen_draw_pull_down_menu('style_search', $style_search,$_GET['style_search']);
            			if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
            			    $keywords = zen_db_input(zen_db_prepare_input($_GET['search']));
            			    echo '<br />' . TEXT_INFO_SEARCH_DETAIL_FILTER . $keywords;
            			}
            
            			?>
            			<?php rie('From')?>
            			<input type="text" class="datepicker" name="start_date" />
            			<?php rie('To')?>
            			<input type="text" class="datepicker" name="end_date" />
            			<?php 
            			rie('Order Status');
            			echo zen_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => ri('All Orders'))), $orders_statuses), $_GET['status'], '');
            
            			echo '<input type="submit" value="Search" name="submit_search"/></form>';
			        ?> <!-- ri -->
					</td>
				</tr>
				<tr>
					<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td valign="top"><table border="0" width="100%" cellspacing="0"
										cellpadding="2">
										<tr class="dataTableHeadingRow">
											<td class="dataTableHeadingContent" align="left"><?php rie('ID'); ?>
											</td>
											<td class="dataTableHeadingContent" align="center"><?php rie('Customer Name'); ?>
											</td>
											<td class="dataTableHeadingContent" align="center"><?php rie('Purchase Date'); ?>
											</td>
											<td class="dataTableHeadingContent" align="center"><?php rie('Model'); ?>
											</td>
											<td class="dataTableHeadingContent" align="center"><?php rie('Name'); ?>
											</td>
											<td class="dataTableHeadingContent" align="center"><?php rie('Price'); ?>
											</td>
											<td class="dataTableHeadingContent" align="center"><?php rie('Categoy Name'); ?>
											</td>
										</tr>
										<?php
										if(isset($orders))
										while(!$orders->EOF){
										    echo '<tr>
							<td>'. $orders->fields['id'] .'</td>
							<td>'. $orders->fields['customers_name'] .'</td>
							<td>'. $orders->fields['date_purchased'] .'</td>
							<td>'. $orders->fields['model'] .'</td>
							<td>'. $orders->fields['products_name'] .'</td>
							<td>'. $orders->fields['price'] .'</td>
							<td>'. $orders->fields['categories_name'] .'</td>
					</tr>';
										    $orders->MoveNext();
										}?>
									</table></td>
							</tr>
						</table>
					</td>
				</tr>

			</table>
		</td>
	</tr>

</table>
