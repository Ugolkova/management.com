<form action="<?php echo URL; ?>users/add/" method="POST" class="publishForm fieldsForm usersForm">
    <input type="hidden" name="token" value="<?php echo $this->token; ?>" />
    
    <table>
        <input type="hidden" name="owner_id" value="<?php echo $this->user['owner_id']; ?>" />
        <thead>
            <tr>
                <th colspan="2">User Data</th>
            </tr>
        </thead>
        <tbody>
            <!-- Standard User Data -->
            <tr>
                <td><label class="required">User Name</label></td>
                <td><input type="text" name="user_name" value="<?php echo $this->user['user_name']; ?>" class="<?php echo $this->isErrorField("user_name") ? 'error' : ''; ?>" /></td>
            </tr>
            <tr>
                <td><label class="required">User Login</label></td>
                <td><input type="text" name="user_login" value="<?php echo $this->user['user_login']; ?>" class="<?php echo $this->isErrorField("user_login") ? 'error' : ''; ?>" /></td>
            </tr>
            <tr>
                <td><label class="required">User Email</label></td>
                <td><input type="text" name="user_email" value="<?php echo $this->user['user_email']; ?>" class="email <?php echo $this->isErrorField("user_email") ? 'error' : ''; ?>" /></td>
            </tr>
            <tr>
                <td><label class="required">User Skype</label></td>
                <td><input type="text" name="user_skype" value="<?php echo $this->user['user_skype']; ?>" class="<?php echo $this->isErrorField("user_skype") ? 'error' : ''; ?>" /></td>
            </tr>

            <!-- END Standard User Data -->
            <?php if( COUNT( $this->user_fields ) ): ?>
            <tr>
                <th colspan="2">User Fields</th>
            </tr>
            <?php endif; ?>
            
            <?php foreach ($this->user_fields as $field): ?>
            <tr>
                <td><?php echo $field['label']; ?></td>
                <td>
                    <small><?php echo $field['instruction']; ?></small>
                    <?php echo $field['tag']; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>    
    </table>
    
    <p><input type="submit" name="submit" value="Submit" /></p>
</form>