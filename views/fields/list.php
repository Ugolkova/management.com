<a href="<?php echo URL; ?>fields/add" class="button add">Add field</a>

<form action="<?php echo URL; ?>/users/search" class="searchForm">
    <fieldset>
        <legend>Total fields: <?php echo $this->fieldsCount; ?></legend>
        <input type="text" value="" placeholder="Keywords" />
        <input type="submit" name="submit" value="" />

        <div class="clear"></div>

        <select name="results_count">
            <option value="10">10 results</option>
            <option value="20">20 results</option>
            <option value="50">50 results</option>
            <option value="100">100 results</option>
            <option value="150">150 results</option>
        </select>    
    </fieldset>
</form>    

<?php if( empty($this->fields) ) : ?>
    <p>There are no entries matching the criteria you selected.</p>
<?php else: ?>               
<form action="<?php echo URL . "fields/edit/" ?>" method="POST">
    <?php echo $this->pagination; ?>
    
    <input type="hidden" name="token" value="<?php echo $this->token; ?>" />
    <table class="fields">
        <thead>
            <tr>
                <th>#</th>
                <th>Label</th>
                <th>Type</th>
                <th><input type="checkbox" name="check_all" /></th>
            </tr>
        </thead>
        <tbody>                       
            <?php foreach($this->fields as $field): ?>
            <tr>
                <td><?php echo $field['field_id']; ?></td>
                <td><a href="<?php echo URL . "fields/edit/" . $field['field_id']; ?>" title="<?php echo $field['field_label']; ?>"><?php echo $field['field_label']; ?></a></td>
                <td><?php echo $field['field_type']; ?></td>
                <td><input type="checkbox" name="field_id[]" value="<?php echo $field['field_id']; ?>" /></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php echo $this->pagination; ?>

    <div class="tableSubmit">
        <input type="submit" name="submit_action" value="Submit" />
        <select name="action">
            <option value="edit">edit selected</option>
            <option value="delete">delete selected</option>
        </select>    
    </div>    
</form>
<?php endif; ?>    