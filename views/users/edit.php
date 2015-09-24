<a href="<?php echo URL; ?>users/add" class="button add">Add User</a>

<form action="<?php echo URL; ?>users/edit/<?php echo ( ($this->user_id !== NULL) ? $this->user_id . '/' : '' ) ; ?>" method="POST" class="publishForm fieldsForm usersForm">
    <input type="hidden" name="token" value="<?php echo $this->token; ?>" />
    
    <?php foreach($this->users as $k => $user): ?>    
    <table>
        <input type="hidden" name="owner_id[<?php echo $k; ?>]" value="<?php echo $user['owner_id']; ?>" />
        <input type="hidden" name="user_id[<?php echo $k; ?>]" value="<?php echo $user['user_id']; ?>" />
        
        <?php if( Session::get('user_type') !== 'admin' ): ?>
        <input type="hidden" name="user_type[<?php echo $k; ?>]" value="user" />
        <?php endif; ?>
        <thead>
            <tr>
                <th colspan="2">User Data</th>
            </tr>
        </thead>
        <tbody>
            <!-- Standard User Data -->
            <tr>
                <td><label class="required">User Name</label></td>
                <td>
                    <input type="text" name="user_name[<?php echo $k; ?>]" value="<?php echo $user['user_name']; ?>" class="<?php echo $this->isErrorField("user_name[$k]") ? 'error' : ''; ?>" <?php echo $this->disabledStandardFields[$k]; ?> required />
                </td>
            </tr>
            <tr>
                <td><label class="required">User Login</label></td>
                <td>
                    <input type="text" name="user_login[<?php echo $k; ?>]" value="<?php echo $user['user_login']; ?>" class="<?php echo $this->isErrorField("user_login[$k]") ? 'error' : ''; ?>" <?php echo $this->disabledStandardFields[$k]; ?> required />
                </td>
            </tr>
            <tr>
                <td><label class="required">User Email</label></td>
                <td><input type="text" name="user_email[<?php echo $k; ?>]" value="<?php echo $user['user_email']; ?>" class="email <?php echo $this->isErrorField("user_email[$k]") ? 'error' : ''; ?>" <?php echo $this->disabledStandardFields[$k]; ?> required /></td>
            </tr>
            <tr>
                <td><label class="required">User Skype</label></td>
                <td><input type="text" name="user_skype[<?php echo $k; ?>]" value="<?php echo $user['user_skype']; ?>" class="<?php echo $this->isErrorField("user_skype[$k]") ? 'error' : ''; ?>" <?php echo $this->disabledStandardFields[$k]; ?> required /></td>
            </tr>

            <?php if( Session::get('user_type') === 'admin' ): ?>
            <tr>
                <td><label class="required">User Type</label></td>
                <td>
                    <select name="user_type[<?php echo $k; ?>]">
                        <option value="admin"<?php if( $user['user_type'] == 'admin' ): ?> selected="selected"<?php endif; ?>>Admin</option>
                        <option value="manager"<?php if( $user['user_type'] == 'manager' ): ?> selected="selected"<?php endif; ?>>Manager</option>
                        <option value="user"<?php if( $user['user_type'] == 'user' ): ?> selected="selected"<?php endif; ?>>User</option>
                    </select>
                </td>
            </tr>
            <?php endif; ?>

            <!-- END Standard User Data -->
            <?php if( COUNT( $this->user_fields[$k] ) ): ?>
            <tr>
                <th colspan="2">User Fields</th>
            </tr>
            <?php endif; ?>
            
            <?php foreach ($this->user_fields[$k] as $field): ?>
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
    
    <?php if( COUNT( $this->lmUsers[$k] ) ) : ?>
    <strong>LM Users</strong>
    <table class="relatedUsers">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Skype</th>
            </tr>
        </thead>
        <tbody>                       
            <?php foreach($this->lmUsers[$k] as $lmUser): ?>
            <tr>
                <td><?php echo $lmUser['user_id']; ?></td>
                <td>
                    <a href="<?php echo URL . "users/edit/" . $lmUser['user_id']; ?>" 
                       title="<?php echo $lmUser['user_name']; ?>">
                        <?php echo $lmUser['user_name']; ?></a>
                </td>
                <td><a href="mailto:<?php echo $lmUser['user_email']; ?>"><?php echo $lmUser['user_email']; ?></a></td>
                <td><a href="skype:<?php echo $lmUser['user_skype']; ?>#call" class="skype"><?php echo $lmUser['user_skype']; ?></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <?php if( COUNT( $this->pmUsers[$k] ) ) : ?>
    <strong>PM Users</strong>
    <table class="relatedUsers">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Skype</th>
            </tr>
        </thead>
        <tbody>                       
            <?php foreach($this->pmUsers[$k] as $lmUser): ?>
            <tr>
                <td><?php echo $lmUser['user_id']; ?></td>
                <td>
                    <a href="<?php echo URL . "users/edit/" . $lmUser['user_id']; ?>" 
                       title="<?php echo $lmUser['user_name']; ?>">
                        <?php echo $lmUser['user_name']; ?></a>
                </td>
                <td><a href="mailto:<?php echo $lmUser['user_email']; ?>"><?php echo $lmUser['user_email']; ?></a></td>
                <td><a href="skype:<?php echo $lmUser['user_skype']; ?>#call" class="skype"><?php echo $lmUser['user_skype']; ?></a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>    
    
    <?php if( COUNT($this->users) > 1 AND $user !== end($this->users) ): ?>
    <hr />
    <?php endif; ?>
    <?php endforeach; ?>

    
    <p><input type="submit" name="submit" value="Submit" /></p>
</form>