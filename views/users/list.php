<a href="<?php echo URL; ?>users/add" class="button add">Add User</a>

<form action="<?php echo URL; ?>users/search/" class="searchForm">
    <fieldset>
        <legend>Total users: <?php echo $this->usersCount; ?></legend>
        <input type="text" name="key" value="" placeholder="Keywords" />
        <input type="submit" value="" />

        <div class="clear"></div>
        <b>User Group</b>
        <select>
            <option value="list">All Users</option>
            <option value="lm_list">LM Users</option>
            <option value="pm_list">PM Users</option>
        </select>

        <select name="results_count">
            <option value="10">10 results</option>
            <option value="20">20 results</option>
            <option value="50">50 results</option>
            <option value="100">100 results</option>
            <option value="150">150 results</option>
        </select>    
    </fieldset>
</form>    

<?php if( empty($this->users) ) : ?>
    <p>There are no entries matching the criteria you selected.</p>
<?php else: ?>               
<form action="<?php echo URL . "users/edit/" ?>" method="POST">
    <?php echo $this->pagination; ?>
    
    <input type="hidden" name="token" value="<?php echo $this->token; ?>" />
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Skype</th>
                <th>Current Project</th>
                <th>Project Manager</th>
                <th>Line Manager</th>
                <th><input type="checkbox" name="check_all" /></th>
            </tr>
        </thead>
        <tbody>                       
            <?php foreach($this->users as $user): ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><a href="<?php echo URL . "users/edit/" . $user['user_id']; ?>" title="<?php echo $user['user_name']; ?>"><?php echo $user['user_name']; ?></a></td>
                <td><a href="<?php echo $user['user_email']; ?>"><?php echo $user['user_email']; ?></a></td>
                <td><a href="skype:<?php echo $user['user_skype']; ?>#call" class="skype"><?php echo $user['user_skype']; ?></a></td>
                <td><a href="<?php echo URL ?>projects/<?php echo $user['user_id']; ?>" title="Expensify">Expensify</a></td>
                <td><a href="<?php echo URL; ?>users/show/<?php echo $user['lm_id']; ?>" title="<?php echo $user['lm_name']; ?>"><?php echo $user['lm_name']; ?></a></td>
                <td><a href="<?php echo URL; ?>users/show/<?php echo $user['pm_id']; ?>" title="<?php echo $user['pm_name']; ?>"><?php echo $user['pm_name']; ?></a></td>
                <td><input type="checkbox" name="user_id[]" value="<?php echo $user['user_id']; ?>" /></td>
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