    <script>
$(document).ready(function(){
    $('select[name*=field_type]').change(function(){
        var fieldType = $(this).val();
        var fieldId = $(this).closest('table').find('input[name*=field_id]').val();
        console.log(fieldId);
        $('.fieldOptions[data-field-id='+ fieldId + ']').fadeOut(0);
        $('.ft_' + fieldType + '[data-field-id='+ fieldId + ']').fadeIn(50);
    }).change();
});    
</script>

<form action="<?php echo URL; ?>fields/edit/<?php echo ( ($this->fieldId !== NULL) ? $this->fieldId . '/' : '' ) ; ?>" method="POST" class="publishForm fieldsForm">
    <input type="hidden" name="token" value="<?php echo $this->token; ?>" />
    
    <?php foreach($this->fields as $k => $field): ?>    
    <table>
        <input type="hidden" name="owner_id[]" value="<?php echo $field['owner_id']; ?>" />
        <input type="hidden" name="field_id[]" value="<?php echo $field['field_id']; ?>" />
        <thead>
            <tr>
                <th colspan="2">Fields Settings</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><label>Type</label></td>
                <td>
                    <select name="field_type[]">
                    <?php foreach($this->fieldTypes as $ft): ?>
                        <option value="<?php echo $ft['type']; ?>"<?php if($ft['type'] == $field['field_type']): ?> selected<?php endif; ?>>
                            <?php echo ucfirst($ft['type']); ?>
                        </option>
                    <?php endforeach; ?>    
                    </select>
                </td>
            </tr>

            <tr>
                <td><label class="required">Field Label</label></td>
                <td><input type="text" name="field_label[]" value="<?php echo $field['field_label']; ?>" /></td>
            </tr>
            <tr>
                <td><label class="required">Field Instructions</label></td>
                <td><textarea name="field_instructions[]" rows="4"><?php echo $field['field_instructions']; ?>
</textarea></td>
            </tr>
            <tr>
                <td><label class="required">Is this a required field?</label></td>
                <td>
                    <input type="radio" name="field_required[<?php echo $k; ?>]" value="1" id="field_required_y<?php echo $k; ?>"<?php if( $field['field_required'] ):?> checked="checked"<?php endif; ?> />
                    <label for="field_required_y<?php echo $k; ?>">Yes</label>

                    <br />

                    <input type="radio" name="field_required[<?php echo $k; ?>]" value="0" id="field_required_n<?php echo $k; ?>"<?php if( !$field['field_required'] ):?> checked="checked"<?php endif; ?> />
                    <label for="field_required_n<?php echo $k; ?>">No</label>
                </td>
            </tr>
        </tbody>    
    </table>

    <?php foreach($this->fieldTypes as $fieldType): ?>
    <div class="ft_<?php echo $fieldType['type']; ?> fieldOptions" data-field-id="<?php echo $field['field_id']; ?>">
        <table>
            <thead>
                <tr>
                    <th colspan="2"><?php echo ucfirst($fieldType['type']); ?> Field Options</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($fieldType['options'] as $option): ?>
                <tr>
                    <td><?php echo $option['label']; ?></td>
                    <td>
                        <?php if($option['instruction'] != ''): ?>
                        <small><?php echo $option['instruction']; ?></small>
                        <?php endif; ?>

                        <input type="text" name="<?php echo $option['short_name'] ?>[]" 
                               value="<?php echo (isset($field['field_settings'][$option['short_name']]) ? 
                               $field['field_settings'][$option['short_name']] : ''); ?>" /></td>
                </tr>
                <?php endforeach; ?>
            </tbody>    
        </table>
    </div>
    <?php endforeach; ?>
    
    <?php if( COUNT($this->fields) > 1 AND $field !== end($this->fields) ): ?>
    <hr />
    <?php endif; ?>
    <?php endforeach; ?>

    
    <p><input type="submit" name="submit" value="Submit" /></p>
</form>