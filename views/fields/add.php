<h1>Add Field</h1>

<section>            
    <form action="<?php echo URL . 'users/add'; ?>" method="POST" class="publishForm fieldsForm">
        <table>
            <thead>
                <tr>
                    <th colspan="2">Fields Settings</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label class="required">Field Label</label></td>
                    <td><input type="text" name="field_label" value="" /></td>
                </tr>
                <tr>
                    <td><label class="required">Field Instructions</label></td>
                    <td><textarea name="field_instructions" rows="4"></textarea></td>
                </tr>
                <tr>
                    <td><label class="required">Is this a required field?</label></td>
                    <td>
                        <input type="radio" name="field_required" value="1" id="field_required_y" />
                        <label for="field_required_y">Yes</label>
                        
                        <br />

                        <input type="radio" name="field_required" value="0" id="field_required_n" />
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
                        <th colspan="2"><?php echo $field['type']; ?> Fields Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($field['options'] as $option): ?>
                    <tr>
                        <td><?php echo $option['label']; ?></td>
                        <td>
                            <small><?php echo $option['instruction']; ?></small>
                            <input type="text" name="<?php echo $option['short_name'] ?>" 
                                   value="<?php echo $option['value']; ?>" /></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>    
            </table>
        </div>
        <?php endforeach; ?>

        <p><input type="submit" name="submit" value="Submit" /></p>
    </form>
</section> 