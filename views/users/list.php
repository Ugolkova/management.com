<script src="<?php echo URL; ?>public/js/users/list.js"></script>

<a href="<?php echo URL; ?>users/add" class="button add">Add User</a>

<form action="<?php echo URL; ?>users/search/" class="searchForm">
    <fieldset>
        <legend>Total users: <?php echo $this->usersCount; ?></legend>
        <input type="text" name="key" value="" placeholder="Keywords" />
        <input type="submit" value="" />

        <div class="clear"></div>
        <b>User Group</b>
        <select name="users_type">
            <option value="">All Users</option>
            <option value="my">My Users</option>
            <option value="lm">LM Users</option>
            <option value="pm">PM Users</option>
        </select>

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

<?php if( empty($this->users) ) : ?>
    <p>There are no entries matching the criteria you selected.</p>
<?php else: ?>               
<form action="<?php echo URL . "users/edit/" ?>" method="POST">
    <?php echo $this->pagination; ?>
    
    <input type="hidden" name="token" value="<?php echo $this->token; ?>" />
    
    <table id="list">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Skype</th>
                <th>Current Project</th>
                <th>Line Manager</th>
                <th>Project Manager</th>
                <th><input type="checkbox" name="check_all" /></th>
            </tr>
        </thead>
        <tbody>                       
            <?php foreach($this->users as $user): ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td>
                    <a href="<?php echo URL . "users/edit/" . $user['user_id']; ?>" 
                       title="<?php echo $user['user_name']; ?>">
                        <?php echo $user['user_name']; ?></a>
                    <?php if( $user['owner_id'] != Session::get('user_id') ): ?>
                    <img src="<?php echo URL; ?>/public/img/creator.png" class="creator"
                         title="<?php echo $user['owner_name']; ?>" />
                    <?php endif; ?>
                </td>
                <td><a href="mailto:<?php echo $user['user_email']; ?>"><?php echo $user['user_email']; ?></a></td>
                <td><a href="skype:<?php echo $user['user_skype']; ?>#call" class="skype"><?php echo $user['user_skype']; ?></a></td>
                <td><a href="<?php echo URL ?>projects/<?php echo $user['user_id']; ?>" title="Expensify">Expensify</a></td>
                <td><a href="<?php echo URL; ?>users/show/<?php echo $user['lm_id']; ?>" title="<?php echo $user['lm_name']; ?>"><?php echo $user['lm_name']; ?></a></td>
                <td><a href="<?php echo URL; ?>users/show/<?php echo $user['pm_id']; ?>" title="<?php echo $user['pm_name']; ?>"><?php echo $user['pm_name']; ?></a></td>
                <td><input type="checkbox" name="user_id[]" value="<?php echo $user['user_id']; ?>" data-remove="<?php if( $user['owner_id'] == Session::get('user_id') || Session::get('user_type') == 'admin' ): ?>true<?php else: ?>false<?php endif; ?>" /></td>
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