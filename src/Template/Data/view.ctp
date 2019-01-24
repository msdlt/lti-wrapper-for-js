<?php $this->assign('title', 'Results'); ?>
<h1>Cerebellar Norrington Table</h1>
<table id="example" class="display" style="width:100%">
	<thead>
		<tr>
			<th>College</th>
			<th>Writing (time)</th>
			<th>Writing (length)</th>
			<th>Join the dots (time)</th>
			<th>Join the dots (length)</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($results['means'] as $key => $value):?>
		<tr>
			<td><?= $key ?></td>
			<td><?= $this->Number->precision($value['writingTimeTaken'],1)?> (<?= $value['writingTimeTakenCounter']?>)</td>
			<td><?= $this->Number->precision($value['writingLength'],1)?> (<?= $value['writingLengthCounter']?>)</td>
			<td><?= $this->Number->precision($value['joiningTimeTaken'],1)?> (<?= $value['joiningTimeTakenCounter']?>)</td>
			<td><?= $this->Number->precision($value['joiningLength'],1)?> (<?= $value['joiningLengthCounter']?>)</td>
		</tr>
	<?php endforeach;?> 
    </tbody>
        <!--tfoot>
            <tr>
                <th>College</th>
				<th>Writing (time)</th>
				<th>Writing (length)</th>
				<th>Dots (time)</th>
				<th>Dots (length)</th>
            </tr>
        </tfoot-->
</table>
<?= $this->Html->link(
    'Go to tasks',
    '/',
    ['class' => 'button', 'target' => '_blank']
); ?>

<?= $this->Html->script('https://code.jquery.com/jquery-3.3.1.js'); ?>
<?= $this->Html->script('https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'); ?>

<?= $this->Html->scriptBlock('$(document).ready(function() {$("#example").DataTable({"paging":   false, "info":     false});} );', ["defer" => true]); ?>
