<div id="left">
	<h2>Все таблицы</h2> 
	<?php if (isset($aTableNames) && is_array($aTableNames)): ?> 
	
		<?php if (count($aTableNames)): ?>
			<ul>
				<?php foreach ($aTableNames as $oRow): ?>  
					<li> 
						<a href="<?php echo URL_ROOT . '?q=show_struct&table='.$oRow->Tables_in_gen;?>"><?php echo $oRow->Tables_in_gen;?></a>     
					</li> 
				<?php endforeach; ?>  
			</ul>    
		<?php else: ?>
			<p>в БД нету не одной таблицы</p> 
		<?php endif; ?>

	<?php endif; ?>
</div>   
<div id="center">
	<!-- структура таблицы -->
	<?php if (isset($aColumns) && ($aColumns)): ?> 
		<h2>Структура таблицы <?php echo $tableName;?>:</h2>   
		<table> 
			<thead>
				<tr>
				    <th id="th2">#</th>
				    <th id="th3" class="column">Поле</th>
				    <th id="th4" class="type">Тип</th>
				    <th id="th5" class="collation">Сравнение</th>
				    <th id="th7" class="null">Null</th>
				    <th id="th8" class="default">По умолчанию</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($aColumns as $i=>$oColumn): ?>  
				<tr>
					<?php //var_dump($oColumn);?>
					<td><?php echo $i+1;?></td>   
					<td><?php echo $oColumn->Field; ?></td> 
					<td><?php echo $oColumn->Type; ?></td> 
					<td><?php //echo $oColumn->Null; ?></td>  
					<td><?php echo $oColumn->Null; ?></td> 
					<td><?php echo $oColumn->Default; ?></td> 
	    		</tr>
			<?php endforeach; ?>   
			</tbody>
		</table>  
	<?php elseif (isset($aColumns)): ?>   
		<p>Таблица не найдена.</p>
	<?php endif; ?>
	<!-- /структура таблицы -->

	<!-- Индексы таблицы -->  
	<?php if (isset($aIndexes)): ?>
		<?php if (count($aIndexes)): ?>
		<table>
			<thead>
				<tr>
					<th>Имя индекса</th>
					<th>Тип</th>
					<th>Уникальный</th>
					<th>Упакован</th>
					<th>Поле</th>
					<th>Уникальных элементов</th>
					<th>Сравнение</th>
					<th>NULL</th>
					<th>Комментарий</th> 
				</tr>
			</thead>
			<tbody>
			<?php foreach ($aIndexes as $oIndex): ?> 
				<tr> 
					<td><?php echo $oIndex->Key_name;?></td>
					<td><?php echo $oIndex->Index_type;?></td>
					<td><?php echo ($oIndex->Non_unique) ? 'нет' : 'да';?></td>
					<td><?php echo $oIndex->Packed;?></td>
					<td><?php echo $oIndex->Column_name;?></td>
					<td><?php echo ($oIndex->Non_unique) ? 0 : $oIndex->Cardinality;?></td>   
					<td><?php echo $oIndex->Collation;?></td> 
					<td><?php echo $oIndex->Null;?></td>
					<td><?php echo $oIndex->Comment;?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>  
		</table>  

		<?php else: ?>    
			<p>Таблица <?php echo $tableName;?> не имеет индексов.</p> 
		<?php endif; ?>    
	<?php endif;?>  
	<!-- /Индексы таблицы --> 

	<form action method="post">  
		<input type="text" name="count_generate" size="5" maxlength="5" />   
		<input type="submit" value="сгенерить" onClick="return confirm('Сгенерить '+parseInt(this.form.count_generate.value)+' записей?');" /> 
	</form>

</div>

