<div class="transactions form">
<?php echo $this->Form->create('Transaction'); ?>
	<fieldset>
	
	<script>
		$(document).ready(function () {
			$("#TransactionSubcategoryId").chained("#TransactionCategoryId");
			$("#datepicker").datepicker('setDate', new Date());   // nastavenie defaultneho datumu na aktualny
			
			$('#opakovanie_nastavenia').hide();
		    if ($('#Repeat0').attr('checked', true)) {
		        $('.opakovanie_nastavenia').hide();
		    }

		    $('#Repeat0').click(function () {
		        $('#opakovanie_nastavenia').slideUp("slow");
		    });

		    $('#Repeat1').click(function () {
		        $('#opakovanie_nastavenia').slideDown("slow");
		    });
		});
		 
		</script>
	
		<legend><?php echo __('Pridaj transakciu'); ?></legend>
	<?php
		echo $this->Form->input('transaction_type_id', array('options' => array('1' => 'príjem', '2' => 'výdavok'), 'value' => '1', 'type' => 'radio', 'id'=> 'transaction_type_id' , 'legend' => 'Typ transakcie' ));
		echo $this->Form->input('name', array('label' => 'Názov transakcie'));
		echo $this->Form->input('amount', array('label' => 'Suma'));
		//$categories[0] = 'Vyberte kategóriu';
		//ksort($categories);
		//echo $this->Form->input('category_id', array('options' => $categories, 'label' => 'Kategória'), array('id' => 'data[Transaction][category_id]'));
		// echo $this->Form->input('subcategory_id', array('options' => $subcategories, 'label' => 'Podkategória')); ?>
		<select id="TransactionCategoryId" name="data[Transaction][category_id]">
		<option value="">Vyberte kategóriu</option>
		<?php foreach ($categories as $key => $row) {
			echo '<option value="'.$key.'" >'.$row.'</option>';
		}?>
		</select>
		<select id="TransactionSubcategoryId" name="data[Transaction][subcategory_id]">
		<option value="">Vyberte podkategóriu</option>
		<?php foreach ($subcategories as $row) {
			echo '<option value="'.$row['Subcategory']['id'].'" class="'.$row['Subcategory']['category_id'].'">'.$row['Subcategory']['name'].'</option>';
		}?>
		</select>
		<?php echo $this->Form->input('user_id', array('type' => 'hidden','value' => $user));
		//echo $this->Form->input('original_transaction_id');
		echo $this->Form->input('post_date', array('type' => 'text', 'id' => 'datepicker', 'label' => 'Dátum transakcie' ));?>
		<div id='opakovanie'>
		<?php echo $this->Form->input('repeat',
				array(
						'type' => 'radio',
						'options' => array('neopakovať', 'opakovať'),
						'id' => 'repeat',
						'legend' => 'Opakovanie transakcie')); ?> </div>
		<div id='opakovanie_nastavenia'>
		<?php echo $this->Form->input('repeat_every', 
				array(
						'type' => 'select', 
						'options' => array('tyzden' => 'tyždeň', 'mesiac' => 'mesiac', 'rok' => 'rok'), 
						'selected' => 'mesiac',
						'label' => 'Opakovať každý'
						
				)
		); 
		 echo $this->Form->input('number_of_cycles', array('label' => 'Počet opakovaní', 'value' => '0'));
		 
		?></div>
		
		
	
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Transactions'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subcategories'), array('controller' => 'subcategories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subcategory'), array('controller' => 'subcategories', 'action' => 'add')); ?> </li>
	</ul>
</div>
