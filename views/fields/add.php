<script src="<?php echo URL; ?>public/js/fields/add.js"></script>

<form action="<?php echo URL; ?>fields/add" method="POST" class="publishForm fieldsForm">
    <input type="hidden" name="token" value="<?php echo $this->token; ?>" />

    <input type="hidden" name="owner_id" value="<?php echo Session::get( 'user_id' ); ?>" />
    <table>
        <thead>
            <tr>
                <th colspan="2">Fields Settings</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><label>Type</label></td>
                <td>
                    <select name="field_type" class="<?php echo $this->isErrorField('field_type') ? 'error' : ''; ?>">
                    <?php foreach($this->fieldTypes as $ft): ?>
                        <option value="<?php echo $ft['type']; ?>"<?php if($ft['type'] == $this->field['field_type']): ?> selected<?php endif; ?>>
                            <?php echo ucfirst($ft['type']); ?>
                        </option>
                    <?php endforeach; ?>    
                    </select>
                </td>
            </tr>

            <tr>
                <td><label class="required">Field Label</label></td>
                <td><input type="text" name="field_label" value="<?php echo $this->field['field_label']; ?>" class="<?php echo $this->isErrorField('field_label') ? 'error' : ''; ?>" required /></td>
            </tr>
            <tr>
                <td><label>Field Instructions</label></td>
                <td><textarea name="field_instruction" rows="4" class="<?php echo $this->isErrorField('field_instruction') ? 'error' : ''; ?>"><?php echo $this->field['field_instruction']; ?>
</textarea></td>
            </tr>
            <tr>
                <td><label class="required">Is this a required field?</label></td>
                <td>
                    <input type="radio" name="field_required" value="1" id="field_required_y"<?php if( $this->field['field_required'] ):?> checked="checked"<?php endif; ?> />
                    <label for="field_required_y">Yes</label>

                    <br />

                    <input type="radio" name="field_required" value="0" id="field_required_n"<?php if( !$this->field['field_required'] ):?> checked="checked"<?php endif; ?> />
                    <label for="field_required_n">No</label>
                </td>
            </tr>
        </tbody>    
    </table>

    <?php foreach($this->fieldTypes as $field): ?>
    <div id="ft_<?php echo $field['type']; ?>" class="fieldOptions">
        <table>
            <thead>
                <tr>
                    <th colspan="2"><?php echo ucfirst($field['type']); ?> Field Options</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($field['options'] as $option): ?>
                <tr>
                    <td>
                        <label class="<?php if( $option['required'] ): ?>required<?php endif; ?>">
                        <?php echo $option['label']; ?>
                        </label>
                    </td>
                    <td>
                        <?php if($option['instruction'] != ''): ?>
                        <small><?php echo $option['instruction']; ?></small>
                        <?php endif; ?>

                        <input type="text" name="<?php echo $option['short_name'] ?>" 
                               value="<?php echo $option['value']; ?>" data-required="<?php echo $option['required']; ?>" /></td>
                </tr>
                <?php endforeach; ?>
            </tbody>    
        </table>
    </div>
    <?php endforeach; ?>

    <p><input type="submit" name="submit" value="Submit" /></p>
</form>