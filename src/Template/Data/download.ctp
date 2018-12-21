<?php $this->assign('filename', "NeuroSimResults_" . date("Y-m-d") . ".xls"); ?>

<table>
    <tr>
        <th>Username</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Last Activity</th>
		<th>Attempted</th>
		<th>Completed</th>
		<th>Not Completed</th>
        <?php 
            foreach($cases as $case):
                echo "<th>" . $case['label'] . "</th>";
            endforeach;
        ?>
    </tr>
    
    <?php foreach($results as $result): 
        $mdate = $result['modified']['date'];
        $mtime = $result['modified']['time'];
    ?>
		<tr>
            <td><?php echo $result['username']?></td>
            <td><?php echo $result['firstname']?></td>
            <td><?php echo $result['lastname']?></td>
            <td><?php echo $mdate['year'] . "-" . $mdate['month'] . "-" . $mdate['day'] . " " . $mtime['hour'] . ":" . $mtime['minute']?></td>
            <td><?php echo $result['attempted']?></td>
            <td><?php echo $result['completed']?></td>
            <td><?php echo $result['not_completed']?></td>
            
            <?php 
                foreach($cases as $case):
                    echo "<td>" . (isset($result['data'][$case['name']])?$result['data'][$case['name']]:"-") . "</td>";
                endforeach;
            ?>
		</tr>
	<?php endforeach; ?>
</table>