<a href="<?php echo URL; ?>fields/add" class="button add">Add Field</a>

<form action="<?php echo URL; ?>fields/search" class="searchForm">
    <fieldset>
        <legend>Total fields: <?php echo $this->fieldsCount; ?></legend>
        <input type="text" name="key" value="<?php echo $this->searchKey; ?>" placeholder="Keywords" />
        <input type="submit" value="" />

        <div class="clear"></div>

        <select name="results_count">
            <?php foreach($GLOBALS['COUNTS_ENTRIES_AVAILABLE'] as $count): ?>
            <option value="<?php echo $count; ?>" 
                <?php if( Session::get('COUNT_ENTRIES_ON_PAGE') == $count ): ?>
                    selected="selected"<?php endif; ?> >    
                <?php echo $count; ?> result<?php if( $count > 1 ): ?>s<?php endif; ?>
            </option>            
            <?php endforeach; ?>
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
                <td>
                    <a href="<?php echo URL . "fields/edit/" . $field['field_id']; ?>" title="<?php echo $field['field_label']; ?>"><?php echo $field['field_label']; ?></a>
                    <?php if( $field['owner_id'] != Session::get('user_id') ): ?>
                    <img src="<?php echo URL; ?>/public/img/creator.png" class="creator"
                         title="<?php echo $field['owner_name']; ?>" />
                    <?php endif; ?>
                </td>
                <td><?php echo $field['field_type']; ?></td>
                <td><input type="checkbox" name="field_id[]" value="<?php echo $field['field_id']; ?>" data-remove="<?php if( $field['owner_id'] == Session::get('user_id') || Session::get('user_type') == 'admin' ): ?>true<?php else: ?>false<?php endif; ?>" /></td>
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