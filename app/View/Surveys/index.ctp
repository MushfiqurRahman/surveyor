<div class="surveys index">
	<h2><?php echo __('Surveys');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('house_id');?></th>
			<th><?php echo $this->Paginator->sort('representative_id');?></th>	
			<th><?php echo $this->Paginator->sort('survey_counter');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('phone');?></th>
			<th><?php echo $this->Paginator->sort('age');?></th>
			<th><?php echo $this->Paginator->sort('adc');?></th>
			<th><?php echo $this->Paginator->sort('occupation_id');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($surveys as $survey): ?>
	<tr>
		<td><?php echo h($survey['Survey']['house_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($survey['Representative']['name'], array('controller' => 'representatives', 'action' => 'view', $survey['Representative']['id'])); ?>
		</td>
		<td><?php echo h($survey['Survey']['survey_counter']); ?>&nbsp;</td>
		<td><?php echo h($survey['Survey']['name']); ?>&nbsp;</td>
		<td><?php echo h($survey['Survey']['phone']); ?>&nbsp;</td>
		<td><?php echo h($survey['Survey']['age']);?>&nbsp;</td>
		<td><?php echo h($survey['Survey']['adc']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($survey['Occupation']['title'], array('controller' => 'occupations', 'action' => 'view', $survey['Occupation']['id'])); ?>
		</td>
		<td><?php echo h($survey['Survey']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $survey['Survey']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $survey['Survey']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $survey['Survey']['id']), null, __('Are you sure you want to delete # %s?', $survey['Survey']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Survey'), array('action' => 'add')); ?></li>
		
		<li><?php echo $this->Html->link(__('List Representatives'), array('controller' => 'representatives', 'action' => 'index')); ?> </li>		
	</ul>
</div>
