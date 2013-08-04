<section class="title">
    <h4>Page: <?php echo $title; ?></h4>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open(site_url('admin/sidenav/page_update/' . $options['page_id'])); ?>
        <div class="form_inputs">
            <ul>
                <li>
                    <label for="title">
                        Title
                        <small>Text to display inside navigation link instead of page title</small>
                    </label>
                    <div class="input">
                        <?php echo form_input(array('name' => 'title', 'value' => $options['title'])); ?>
                    </div>
                </li>
                <li>
                    <label for="hide">
                        Hide
                        <small>Hide page from navigation</small>
                    </label>
                    <div class="type-checkbox">
                        <?php echo form_checkbox(array('name' => 'hide', 'value' => 1, 'checked' => $options['hide'])); ?>
                    </div>
                </li>
                <li>
                    <label for="hide_children">
                        Hide children
                        <small>Hide all children from navigation</small>
                    </label>
                    <div class="type-checkbox">
                        <?php echo form_checkbox(array('name' => 'hide_children', 'value' => 1, 'checked'=> $options['hide_children'])); ?>
                    </div>
                </li>
                <li>
                    <label for="hide_menu">
                        Hide menu
                        <small>Hide navigation menu in sidebar</small>
                    </label>
                    <div class="type-checkbox">
                        <?php echo form_checkbox(array('name' => 'hide_menu', 'value' => 1, 'checked'=> $options['hide_menu'])); ?>
                    </div>
                </li>
            </ul>
        </div>

        <div class="buttons">
            <?php echo form_button(array('type' => 'submit', 'name' => 'submit', 'class' => 'btn blue', 'content' => 'Save', 'value' => 'save')); ?>
            <?php echo form_button(array('type' => 'submit', 'name' => 'submit', 'class' => 'btn blue', 'content' => 'Save & Exit', 'value' => 'esave')); ?>
        </div>

        <?php echo form_close(); ?>
    </div>
</section>