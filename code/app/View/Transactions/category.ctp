<div class="transactions index">
	<h2><?php echo __('Transakcie podľa kategórií'); ?></h2>
	
	<script>
  $(function() {
    $( "#from" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#from" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
    $( "#to" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
    $( "#to" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
  });
  </script>
  
  <div class="chart">
	
	<div id="columnwrapper" style="display: block; float: left; width:90%; margin-bottom: 20px;"></div>
    <div class="clear"></div>	
	
	<?php echo $this->HighCharts->render('Column Chart'); ?>

</div>
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('post_date','Dátum'); ?></th>
			<th><?php echo $this->Paginator->sort('transaction_type_id', 'Typ transakcie'); ?></th>
			<th><?php echo $this->Paginator->sort('name','Názov'); ?></th>
			<th><?php echo $this->Paginator->sort('amount','Suma'); ?></th>
			<th><?php echo $this->Paginator->sort('category_id','Kategória'); ?></th>
			<th><?php echo $this->Paginator->sort('subcategory_id','Subkategória'); ?></th>
			<th><?php echo $this->Paginator->sort('original_transaction_id','ID hlavnej transakcie'); ?></th>
			<th class="actions"><?php echo __('Akcie'); ?></th>
	</tr>
	<?php foreach ($transactions as $transaction): ?>
	<tr>
		<td><?php echo h($transaction['Transaction']['id']); ?>&nbsp;</td>
		<td><?php echo h(CakeTime::format('d.m.Y',$transaction['Transaction']['post_date'])); ?>&nbsp;</td>
		<td><?php echo h($transaction['Transaction']['transaction_type_id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link($transaction['Transaction']['name'], array('action' => 'view', $transaction['Transaction']['id'])); ?>&nbsp;</td>
		<td><?php echo h($transaction['Transaction']['amount']); ?> € &nbsp;</td>
		<td><?php echo $transaction['Category']['name'];?>&nbsp;</td>
		<td><?php echo $transaction['Subcategory']['name']; ?>&nbsp;</td>
		<td><?php echo h($transaction['Transaction']['original_transaction_id']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link($this->Html->image('/img/edit.png', array('alt' => 'Editovať')), array('action' => 'edit', $transaction['Transaction']['id']), array('escape' => false)); ?>
			<?php echo $this->Form->postLink($this->Html->image('/img/deletered.png', array('alt' => 'Zmazať')), array('action' => 'delete', $transaction['Transaction']['id']), array('escape' => false), __('Ste si istý, že chcete zmazať túto transakciu: id # %s?', $transaction['Transaction']['id'])); ?>
			<?php echo $this->Form->postLink($this->Html->image('/img/deleteall.png', array('alt' => 'Zmazať aktuálnu a všetky ďalšie')), array('action' => 'delete_next_repeats', $transaction['Transaction']['id']), array('escape' => false), __('Ste si istý, že chcete zmazať túto transakciu a všetky jej ďalšie opakovania?: id # %s?', $transaction['Transaction']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Stránka {:page} z {:pages}, zobrazuje {:current} záznamov zo {:count} celkovo, začína na zázname {:start}, končí na zázname {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('naspäť'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('ďalej') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<?php echo $this->Form->create('Filter'); 
	  echo $this->Form->input('from_date', array('type' => 'text', 'id' => 'from', 'label' => 'Od:' ));
	  echo $this->Form->input('to_date', array('type' => 'text', 'id' => 'to', 'label' => 'Do:' ));
	  //echo $this->Form->input('year_month_day', array('options' => array('1' => 'ročný', '2' => 'mesačný', '3' => 'denný'), 'value' => '2', 'type' => 'radio', 'id'=> 'year_month_day' , 'legend' => 'Rozdeliť na:' ));
	  echo $this->Form->end(__('Filtruj')); 
	  print_r($this->request->data); ?>
	<ul>
		<li><?php echo $this->Html->link(__('New Transaction'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Subcategories'), array('controller' => 'subcategories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('New Subcategory'), array('controller' => 'subcategories', 'action' => 'add')); ?> </li>
	</ul>
</div>
