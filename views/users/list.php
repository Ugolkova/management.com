<!DOCTYPE html>
<html>
    <head>
        <title>Users</title>
        <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/default.css" />
    </head>
    <body>
        <h1>User List</h1>
        
        <section>            
            <form action="<?php echo URL; ?>/users/search" class="searchForm">
                <fieldset>
                    <legend>Total users: 25</legend>
                    <input type="text" value="" placeholder="Keywords" />
                    <input type="submit" name="submit" value="" />
                    
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
            
            <div class="pagination">
                <div class="prev"></div>
                <div class="next"></div>
                <ul>
                    <li><a href="#1">1</a></li>
                    <li><strong>2</strong></li>
                    <li><a href="#3">3</a></li>
                    <div class="clear"></div>
                </ul>
            </div>
            
            <form action="<?php echo URL . "edit/" ?>" method="POST">
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
                        <tr>
                            <td>1</td>
                            <td><a href="<?php echo URL; ?>users/show/123" title="Tetiana Ugolkova">Tetiana Ugolkova</a></td>
                            <td><a href="mailto:tugolkova@cogniance.com">tugolkova@cogniance.com</a></td>
                            <td><a href="skype:ugolkova.t#call" class="skype">ugolkova.t</a></td>
                            <td><a href="<?php echo URL ?>projects/Expensify" title="Expensify">Expensify</a></td>
                            <td><a href="<?php echo URL; ?>users/show/123" title="Alexey Koval">Alexey Koval</a></td>
                            <td><a href="<?php echo URL; ?>users/show/1234" title="Michael Goncharenko">Michael Goncharenko</a></td>
                            <td><input type="checkbox" name="ids[]" value="1" /></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><a href="<?php echo URL; ?>users/show/123" title="Michael Goncharenkol">Michael Goncharenko</a></td>
                            <td><a href="mailto:mgoncharenko@cogniance.com">mgoncharenko@cogniance.com</a></td>
                            <td><a href="skype:mgoncharenko#call" class="skype">mgoncharenko</a></td>
                            <td><a href="<?php echo URL ?>projects/Expensify" title="Expensify">Expensify</a></td>
                            <td><a href="<?php echo URL; ?>users/show/123" title="Alexey Koval">Alexey Koval</a></td>
                            <td><a href="<?php echo URL; ?>users/show/1234" title="Somebody Else">Somebody Else</a></td>
                            <td><input type="checkbox" name="ids[]" value="1" /></td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="pagination">
                    <div class="prev"></div>
                    <div class="next"></div>
                    <ul>
                        <li><a href="#1">1</a></li>
                        <li><strong>2</strong></li>
                        <li><a href="#3">3</a></li>
                        <div class="clear"></div>
                    </ul>
                </div>
                
                <div class="tableSubmit">
                    <input type="submit" name="submit" value="Submit" />
                    <select name="action">
                        <option value="edit">edit selected</option>
                        <option value="delete">delete selected</option>
                    </select>    
                </div>    
            </form>
        </section>    
    </body>
</html>