<div class="surveys index">
	<h2><?php echo __('Surveys');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('campaign_id');?></th>
			<th><?php echo $this->Paginator->sort('house_id');?></th>
			<th><?php echo $this->Paginator->sort('representative_id');?></th>
			<th><?php echo $this->Paginator->sort('mo_log_id');?></th>
			<th><?php echo $this->Paginator->sort('survey_counter');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('phone');?></th>
			<th><?php echo $this->Paginator->sort('age_id');?></th>
			<th><?php echo $this->Paginator->sort('adc');?></th>
			<th><?php echo $this->Paginator->sort('occupation_id');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($surveys as $survey): ?>
	<tr>
		<td><?php echo h($survey['Survey']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($survey['Campaign']['title'], array('controller' => 'campaigns', 'action' => 'view', $survey['Campaign']['id'])); ?>
		</td>
		<td><?php echo h($survey['Survey']['house_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($survey['Representative']['name'], array('controller' => 'representatives', 'action' => 'view', $survey['Representative']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($survey['MoLog']['id'], array('controller' => 'mo_logs', 'action' => 'view', $survey['MoLog']['id'])); ?>
		</td>
		<td><?php echo h($survey['Survey']['survey_counter']); ?>&nbsp;</td>
		<td><?php echo h($survey['Survey']['name']); ?>&nbsp;</td>
		<td><?php echo h($survey['Survey']['phone']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($survey['Age']['id'], array('controller' => 'ages', 'action' => 'view', $survey['Age']['id'])); ?>
		</td>
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
		<li><?php echo $this->Html->link(__('List Campaigns'), array('controller' => 'campaigns', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Campaign'), array('controller' => 'campaigns', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Representatives'), array('controller' => 'representatives', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Representative'), array('controller' => 'representatives', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mo Logs'), array('controller' => 'mo_logs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mo Log'), array('controller' => 'mo_logs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Ages'), array('controller' => 'ages', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Age'), array('controller' => 'ages', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Occupations'), array('controller' => 'occupations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Occupation'), array('controller' => 'occupations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Houses'), array('controller' => 'houses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New House'), array('controller' => 'houses', 'action' => 'add')); ?> </li>
	</ul>
</div>
